<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private $client;
    private $apiKey;
    private $organizationId;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPEN_AI_SECRET');
        
        // Include your organization ID here
        $this->organizationId = 'org-LgmUcrt148B78tohke8oKlaA';
    }

    public function generateChatResponse($messages)
    {
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
                'OpenAI-Organization-ID' => $this->organizationId,
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo-0613',
                'messages' => $messages, // This now includes the custom instruction
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
