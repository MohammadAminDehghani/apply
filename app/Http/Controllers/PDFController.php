<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
//use PDF; // For DOMPDF, use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf as SnappyPDF;

class PDFController extends Controller
{
    public function generatePDF(int $jobId)
    {
        // Load JSON data (e.g., from file or database)
        //$jsonPath = storage_path('app/public/cv.json'); // Adjust path as needed
        $jsonPath = storage_path('app/ai_response/'.$jobId.'.txt'); // Adjust path as needed
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        // Load the Blade template with JSON data DomPDF
        $pdf = PDF::loadView('pdf', ['data' => $jsonData]);

        //$pdf = SnappyPDF::loadView('pdf', ['data' => $jsonData]);

        // Return the generated PDF for download
        return $pdf->download('resume.pdf');
    }
}

