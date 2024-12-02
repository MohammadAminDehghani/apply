<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.openai.key');
    }

    public function API_to_AI($content, $model = 'gpt-3.5-turbo', $max_tokens = 4096 )
    {
        // if you want to pay more!
        $model = 'gpt-4o-2024-08-06';

        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $content,
                    ],
                ],
                'max_tokens' => $max_tokens,
            ],
        ]);
        $res_json =  json_decode($response->getBody(), true);
        //return $res_json;
        return $res_json['choices'][0]['message']['content'];
    }

    public function API_to_AI_ASSISTANT($content, $assistantId = 'asst_kXeivnEVUmV9eKckTXyPbaVk', $max_tokens = 4000)
    {
        $response = $this->client->post('https://api.openai.com/v2/assistants/' . $assistantId . '/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are reading professor data. If certain fields like "news_and_media", "publications", or "affiliation" are not found, return an empty string. Provide specific details for fields like "publications" by including the title, journal, year, and link. For fields like "education" and "degrees", list the highest degrees obtained. If "research_interests" or "projects" appear, focus on recent or ongoing work.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $content,
                    ],
                ],
                'max_tokens' => $max_tokens,
            ],
        ]);

        $res_json = json_decode($response->getBody(), true);
        return $res_json['choices'][0]['message']['content'];
    }

}

