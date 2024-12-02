<?php

namespace App\Http\Controllers;

use App\helpers\services\WebScraperService;
use Illuminate\Http\Request;

class WebScraperController extends Controller
{
    public function scrape(WebScraperService $scraper)
    {

        $url = 'https://www.concordia.ca/ginacody/electrical-computer-eng/about/faculty-members.html';
        $html = $scraper->fetchPage($url);
        $professors = $scraper->parseContent($html);

        //dd(3);

        return view('professors', ['professors' => $professors]);
    }
}
