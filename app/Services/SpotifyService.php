<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SpotifyService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    
    private function authenticate()
    {
        $response = $this->client->request('POST', 'https://accounts.spotify.com/api/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('79fce09ecd854abc88b57c7a72a6f4bd:1a9ebd275a964aefaf59de9d7052bbca'),
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $this->accessToken = $data['access_token'];
        
        return $data['access_token'];
    }

    public function getTrackInfo($trackId, $accessToken)
    {
        if (!$accessToken) {
            $accessToken = $this->authenticate();
        }

        $response = $this->client->request('GET', "https://api.spotify.com/v1/tracks/{$trackId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    public function findSongDetails($songName, $accessToken)
    {
        if (!$accessToken) {
            $accessToken = $this->authenticate();
        }

        $response = $this->client->request('GET', 'https://api.spotify.com/v1/search', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
            ],
            'query' => [
                'q' => $songName,
                'type' => 'track',
                'limit' => 2,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        $track = $data['tracks']['items'][0] ?? null;

        if (!$track) {
            return null;
        }

        $artistName = $track['artists'][0]['name'] ?? 'Unknown Artist'; // Assuming first artist as primary

        return [
            'name' => $track['name'],
            'artist' => $artistName,
            'image' => $track['album']['images'][0]['url'] ?? null,
            'spotifyUrl' => $track['external_urls']['spotify'] ?? null,
            'previewUrl' => $track['preview_url'] ?? null, // URL to preview the song
        ];
    }

    public function fetchAccountDetails($accessToken)
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return null;
        }
    }

    public function getMostListenedTracks($accessToken, $limit = 4)
    {
        $response = $this->client->request('GET', 'https://api.spotify.com/v1/me/top/tracks', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
            ],
            'query' => [
                'limit' => $limit,
                'time_range' => 'short_term', // Get tracks from the past month
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['items'] ?? [];
        } else {
            return null;
        }
    }

    public function createPlaylist($userId, $name, $description = '', $public = false, $accessToken)
    {
        $response = $this->client->request('POST', "https://api.spotify.com/v1/users/{$userId}/playlists", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $name,
                'description' => $description,
                'public' => $public,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function addTracksToPlaylist($playlistId, array $trackUris, $accessToken)
    {
        if (empty($trackUris)) {
            return ['error' => 'No tracks provided'];
        }

        $response = $this->client->request('POST', "https://api.spotify.com/v1/playlists/{$playlistId}/tracks", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'uris' => $trackUris,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
