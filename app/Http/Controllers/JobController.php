<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Services\OpenAIService;
use App\Services\WebScraperService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class JobController extends Controller
{

    protected OpenAIService $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $jobs = Job::all();

        return view('job.index', ['jobs' => $jobs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($link = $request->link){
            $job = Job::where('link', $link)->first();
            if($job){
                return "it is exist. job id is ".$job->id;
            }
        }

        $data = $request->only('title', 'description','link','website','salary', 'status', 'note');
        Job::create($data);
        return redirect('job');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect('job');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function cv_creator(Job $job, request $request){


        //dump($request->cl);
        $request->cl === "on" ? $cl = true : $cl = false;
        //dd($cl);
        $jsonPath = storage_path('app/public/cv_for_sending_to_ai.json');
        $cvJsonData = file_get_contents($jsonPath);
        //$cv = json_decode($cvJsonData, true);

        $content = $this->content($job, $cvJsonData, $cl);

        //dd($content);

        $cv_ai_response_json_string = $this->openAIService->API_to_AI($content);

        //save file in storage
        $this->save_file_for_log('ai_response', $cv_ai_response_json_string, $job->id);

        return redirect('job');
    }



    public function generate_pdf(Job $job)
    {



        function containsString(array $array, string $searchString): bool {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (containsString($value, $searchString)) {
                        return true;
                    }
                } elseif (strpos((string) $value, $searchString) !== false) {
                    return true;
                }
            }
            return false;
        }


        // Load JSON data (e.g., from file or database)
        //$jsonPath = storage_path('app/public/cv.json'); // Adjust path as needed
        $jsonPath = storage_path('app/ai_response/'.($job->id).'.txt'); // Adjust path as needed
        //$jsonData = json_decode(file_get_contents($jsonPath), true);
        $jsonData = $this->convertToJson(file_get_contents($jsonPath));


        $jsonData = $this->merge_cv($jsonData);
        // Load the Blade template with JSON data DomPDF

        if(containsString($jsonData, 'comment_for_ai')){
            dd('please check cv');
        }

        $pdf = PDF::loadView('pdf', ['data' => $jsonData]);

        //$pdf = SnappyPDF::loadView('pdf', ['data' => $jsonData]);

        // Return the generated PDF for download
        return $pdf->download(($job->id).'-'.$jsonData["personal_info"]["title"].'.pdf');
    }

    protected function save_file_for_log(string $pre_address, string $text, int $id)
    {
        $filename = $pre_address.'/' . $id . '.txt';
        Storage::disk('local')->put($filename, $text);
    }

    protected function content($job, $cv, $cl = false): string
    {

        $cover_letter = $cl ? ' At the end of cv, there is a key for cover letter, please add cover letter with this job and cv' : '';

//        return "
//            Customize my CV according to the provided job description.
//            Tailor my skills, experience, and projects to align with the job requirements.
//            Respond with the exact CV format (keep all keys, only update the values).
//            Pay close attention to 'comment_for_ai' sections in the CV as they are important.
//            Do not change my total experience; it is exactly 5 years (do not increase it).
//            Ignore empty strings in the CV, keep the keys intact, and do not update these values (especially for locations).
//            I am familiar with cloud like AWS and GCP but I am not an expert."
//            .$cover_letter.
//            "the job title is: ".$job->title." and the job description is: "
//            .$job->description." and the cv json content is ".$cv;


        return "
            Customize my CV according to the provided job description.
            Tailor my skills, experience, and projects to align with the job requirements.
            Respond with the exact CV format (keep all keys, only update the values).

            explanation for cv[skills][hard_skills_tools] and cv[work_experience][projects][tech_stack] =  change tools by priority,
            if some tools are not related remove them and add some new tools or change to exact value like HTML to HTML5 or vice versa.

            explanation for cv[work_experience][projects][achievements] =
                Rewrite and prioritize achievements according to job requirements.
                Retain only 3–5 key achievements that align with the job's focus.
                Emphasize technologies I know: PHP/Laravel, Node.js/Express.js, Python/Django, React, Vue, HTML, CSS, JavaScript, Wordpress MySQL, and MongoDB.
                If the job requires expertise outside my skills (e.g., C#, .Net, Java), respond with: 'This job does not match your expertise.'
                For each bullet, describe: 1. the problem, 2. the solution, and 3. the tools/technologies used.
                If the job focuses on a specific stack (e.g., Django + React), adjust the technologies and achievements to reflect that stack.
                Ensure bullets are concise, measurable, and impactful.

            these explanation are valid for all projects.

            Do not change my total experience; it is more than 5 years (do not increase it).
            Ignore empty strings in the CV, keep the keys intact, and do not update these values (especially for locations).
            I am familiar with cloud like AWS and GCP but I am not an expert."
            .$cover_letter.
            "the job title is: ".$job->title." and the job description is: "
            .$job->description." and the cv json content is ".$cv;

    }

    protected function convertToJson($content) {

        // Remove everything before the first '{' and after the last '}'
//        $start = strpos($content, '{');
//        $end = strrpos($content, '}');
//        $between = substr($content, $start, $end);
//        if ($start === false || $end === false || $start > $end) {
//            throw new Exception('Invalid JSON format');
//        }
//        Replace single quotes with double quotes
//        $content = preg_replace("/'/", '"', $between);

        // Remove start pattern
        $content = preg_replace('/^\s*```json\s*/', '', $content);
        // Remove end pattern
        $content = preg_replace('/\s*```\s*$/', '', $content);

        // Use json_decode
        $data = json_decode($content, true);

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON Error: ' . json_last_error_msg());
        }

        return $data;
    }

    protected function merge_cv($ai_cv){

        // Sample JSON data (replace with actual file loading)
        $jsonPath = storage_path('app/public/cv.json');
        $cv_srting = file_get_contents($jsonPath);
        $cv = $this->convertToJson($cv_srting);

    // Recursive function to update JSON object
    function updateJson($cv, $ai_cv) {
        foreach ($cv as $key => $value) {
            // Check if $ai_cv has the same key
            if (array_key_exists($key, $ai_cv)) {
                if (is_array($value) && is_array($ai_cv[$key])) {
                    // Recursively update nested objects or arrays
                    $cv[$key] = updateJson($value, $ai_cv[$key]);
                } else {
                    // Update value if $ai_cv[key] is not empty
                    $cv[$key] = $ai_cv[$key] !== '' ? $ai_cv[$key] : $value;
                }
            }
        }
        return $cv;
    }

    // Update JSON object
    $updatedJson = updateJson($cv, $ai_cv);

    // Save the updated JSON to a file (optional)
    //file_put_contents('updated.json', json_encode($updatedJson, JSON_PRETTY_PRINT));

    //        if($ai_cv["skills"]["hard_skills_backend"][0] === ""){
    //            unset($updatedJson["skills"]["hard_skills_backend"]);
    //        }
        return $updatedJson;
    }


}
