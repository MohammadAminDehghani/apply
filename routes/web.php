<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\WebScraperController::class,'scrape']);


Route::get('/pdfd', [PDFController::class, 'generatePDF']);
//Route::get('/', function () {
//
//
//    return view('welcome');
//});

Route::get('/pdf', function () {
    //$jsonPath = storage_path('app/public/cv.json'); // Adjust path as needed
    $jsonPath = storage_path('app/ai_response/6.txt'); // Adjust path as needed
    $jsonData = json_decode(file_get_contents($jsonPath), true);
    return view('pdf',['data'=>$jsonData]);
});

Route::resource('job', \App\Http\Controllers\JobController::class);

Route::post('job/{job}/create-cv',[\App\Http\Controllers\JobController::class,"cv_creator"])->name('job.create-cv');
Route::get('job/{job}/generate-pdf',[\App\Http\Controllers\JobController::class,"generate_pdf"])->name('job.generate-pdf');

