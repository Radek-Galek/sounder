<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    public function generateSimilarSongs(Request $request, OpenAIService $openAIService)
    {
        $prompt = $request->input('prompt');

        // Use the retrieved prompt in the messages array
        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        $response = $openAIService->generateChatResponse($messages);

        return response()->json($response);
    }
}