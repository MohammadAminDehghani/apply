<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Resume</title>
    <style>

        @page { margin: 24px; }

        body {
            font-family: Arial, sans-serif;
            font-size: 0.8rem;
            line-height: 1.4;
            margin: 4px;
        }
        h1 {
            color: #040733;
            text-align: center;
            font-size: 36px;
            margin-top: 0;
            margin-bottom: 0;
        }
        h2 {
            color: rgba(12, 15, 80, 0.97);
            font-size: 20px;
            margin: 0;
        }
        h3 {
            color: rgba(4, 6, 35, 0.75);
            text-align: center;
            font-size: 24px;
            font-weight: lighter;
            margin-top: 0;
            margin-bottom: 0;
        }
        h4 {
            color: rgba(0, 0, 0, 0.89);
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 0;
        }
        .container {
            width: 100%;
            margin: 0 10px;
        }
        .columns {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            vertical-align: top;
            padding: 5px;
            width: 50%;
        }
        .column3 {
            display: table-cell;
            vertical-align: top;
            padding: 2px;
            width: 33.33333%;
        }
        ul {
            margin-top: 4px;
            padding: 0;
            list-style: none;

        }
        li {
            margin-top: 0;
            margin-bottom: 0px;
            padding-left: 16px;
            list-style: none;
        }
        ul li {
            margin-top: 0;
            margin-bottom: 5px;
            padding-left: 0.5rem;
        }
        .justify{
            text-align: justify;
            text-justify: inter-word;
        }
        hr.mahv {
            border-top: 1px solid #356e92c9;
        }
    </style>
{{--    <link href="{{ public_path('bootstrap.css') }}" rel="stylesheet">--}}
{{--    <link href="./bootstrap.css" rel="stylesheet">--}}

</head>
<body class="container" style="margin: 0">

{{-- info --}}
<div class="section">
    <div>
        <span style="color: #040733;text-align: center;font-size: 36px;margin-top: 0;margin-bottom: 0;">
            {{ $data['personal_info']['name'] }}
        </span>
        <span>/</span>
        <span style="color: rgba(4, 6, 35, 0.75); text-align: center; font-size: 24px; font-weight: lighter; margin: 0;">
            {{ $data['personal_info']['title'] }}
        </span>
    </div>
    <hr>
{{--    <h1>{{ $data['personal_info']['name'] }}</h1>--}}
{{--    <h3 style="margin-bottom: 8px">{{ $data['personal_info']['title'] }}</h3>--}}
    <div class="columns">
        <div class="column">
            <strong>Email:</strong> {{ $data['personal_info']['email'] }}
        </div>
        <div class="column">
            <strong>Phone:</strong> {{ $data['personal_info']['phone'] }}
        </div>

    </div>
    <div class="columns">
        <div class="column">
            <strong>Address:</strong> {{ $data['personal_info']['address'] }}
        </div>
        <div class="column">
            <strong>LinkedIn:</strong> <a href="{{ $data['personal_info']['linkedin'] }}">{{ $data['personal_info']['linkedin'] }}</a>
        </div>
    </div>
</div>
<hr>
{{-- summary --}}
<div class="section">
    <h2 style="margin: 0" class="text-danger">Summary</h2>
    <div class="justify">{{ $data['summary'] }}</div>
</div>
<hr>
{{-- skills --}}
<div class="section">
{{--    <h2>Skills</h2>--}}
    <div class="columns">
        <div class="column3">
            <h4>Soft Skills</h4>
            @foreach ($data['skills']['soft_skills'] as $skill)
                <li>{{ $skill }}</li>
            @endforeach
        </div>
        <div class="column3">
            @if(isset($data['skills']['hard_skills_backend']))
                <h4>Hard Skills (backend)</h4>
                @foreach ($data['skills']['hard_skills_backend'] as $skill)
                    <li>{{ $skill }}</li>
                @endforeach
            @endif
        </div>
        <div class="column3">
            <h4>Hard Skills (frontend)</h4>
            @foreach ($data['skills']['hard_skills_frontend'] as $skill)
                <li>{{ $skill }}</li>
            @endforeach
        </div>
    </div>

    <ul>
        <li><strong>tools:</strong> {{ implode(', ', $data['skills']['hard_skills_tools']) }}</li>
    </ul>
</div>
<hr>
{{-- Work Experience --}}
<div class="section">
    <h2>Work Experience</h2>
    @foreach ($data['work_experience'] as $experience)
        <h4>{{ $experience['title'] }} at {{ $experience['company'] }} / {{ $experience['duration'] }} / {{ $experience['location'] }}</h4>
        @foreach ($experience['projects'] as $project)
            <hr class="mahv">
            <div><strong>Project Summary:</strong> {{ $project['summary'] }}</div>

                @foreach ($project['achievements'] as $achievement)
                <li class="justify" style="margin-bottom: 4px;"><span style="color: #0a53be; margin-right: 4px; font-weight: bold; font-size: 12px">*</span>{{ $achievement }}</li>
                @endforeach
            <ul>
                <li><strong>stack:</strong> {{ implode(' / ', $project['tech_stack']) }}</li>
            </ul>

        @endforeach
    @endforeach
</div>
<hr>
{{-- Education --}}
<div class="section">
    <h2>Education</h2>
    @foreach ($data['education'] as $education)
        <div><strong>{{ $education['degree'] }}</strong> - {{ $education['university'] }} ({{ $education['duration'] }})</div>
        <div style="color: rgba(6,38,82,0.71); margin-bottom: 8px;">Rank: {{ $education['rank'] }}</div>
    @endforeach
</div>
<hr>
{{-- Certifications --}}
<div class="section">
    <h2>Certifications and Courses</h2>
    <div class="columns">
        @foreach ($data['certifications'] as $certification)
            @if($loop->iteration % 2 != 0)
            <div class="columns">
            @endif
            <div class="column"><a href="{{$certification['link']}}">{{ $certification['title'] }}</a></div>
            @if($loop->iteration % 2 == 0)
            </div>
            @endif
        @endforeach
    </div>

</div>

</body>
</html>



