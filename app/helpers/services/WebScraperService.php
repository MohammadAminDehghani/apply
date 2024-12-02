<?php

namespace App\helpers\services;

use GuzzleHttp\Client;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;
use function PHPUnit\Framework\isEmpty;

class WebScraperService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
        ]);

    }

    /**
     * @param $node
     * @return stdClass
     */
    private static function getInfo($node): stdClass
    {
        $name = $node->filter('strong')->text() ?? '';
        $affiliation = $node->filter('.title')->text() ?? '';
        $number = $node->filter('ul.list-unstyled li:nth-of-type(1)')->text() ?? '';
        !isEmpty($node->filter('ul.list-unstyled li:nth-of-type(2)'))  ? $location = $node->filter('ul.list-unstyled li:nth-of-type(2)')->text() : $location = '';
        !isEmpty($node->filter('ul.list-unstyled li:nth-of-type(3)')) ? $email = $node->filter('ul.list-unstyled li:nth-of-type(3)')->text() : $email = '';

        // Clean up extracted strings
        $name = trim($name);
        $affiliation = trim($affiliation);
        $number = trim(str_replace('i class="icon-phone"></i>', '', $number));
        $location == '' ?? $location = trim(str_replace(['i class="icon-map-marker"></i>', "\n"], '', $location));
        $email == '' ?? $email = trim(str_replace(['i class="icon-envelope"></i>', "\n"], '', $email));

        // Create an empty object
        $professor = new stdClass();

        // Add professor details to the object properties
        $professor->name = $name;
        $professor->affiliation = $affiliation;
        $professor->number = $number;
        $professor->location = $location;
        $professor->email = $email;

        // Return the object
        return $professor;
    }

    public function fetchPage($url)
    {
        $response = $this->client->get($url);
        return $response->getBody()->getContents();
    }

    public function parseContent($html)
    {
        $url = 'https://www.concordia.ca/';

        $crawler = new Crawler($html, $url);

        // Check if the node list is empty before trying to iterate over it

        try {
            $professors = []; // Initialize an empty array
            $crawler->filter('#content_main_box_box_parsys_accordion_panel > div > div > div > div.c-faculty-list > ul > li')
                ->each(function ($node) use (&$professors) {
                    $professors[] = self::getInfo($node);
                });

            $crawler->filter('#content_main_box_box_parsys_accordion_panel_1371186515 > div > div > div > div.c-faculty-list > ul > li')
                ->each(function ($node) use (&$professors) {
                    $professors[] = self::getInfo($node);
                });

            return $professors;

        } catch (Throwable $e) {
            report($e);
            return false;
        }


    }

}
