<?php

namespace App\Http\Controllers;

use App\Services\SpotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpotifyController extends Controller
{
    public function getTrack(Request $request, SpotifyService $spotifyService, $trackId)
    {
        // Assuming you've stored the access token in the session or a similar persistence layer.
        $accessToken = session('spotify_access_token');
        $trackInfo = $spotifyService->getTrackInfo($trackId, $accessToken);
        return response()->json($trackInfo);
    }

    public function getMostListenedTrack(Request $request)
    {

        $accessToken = session('spotify_access_token') ?? $request->header('Authorization');
        
        // Ensure the Authorization header is provided
        if (!$accessToken) {
            return response()->json(['error' => 'Access Token is required'], 401);
        }
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.spotify.com/v1/me/top/tracks?limit=1');

        if ($response->successful()) {
            $trackData = $response->json();
            return response()->json($trackData['items'][0] ?? []);
        } else {
            return response()->json(['error' => 'Failed to fetch the most listened track'], $response->status());
        }
    }

    public function callback(Request $request, SpotifyService $spotifyService)
    {
        $code = $request->code;
        $accessToken = $this->exchangeCodeForToken($code);

        // Store access token in session or a preferred method of persistence.
        session(['spotify_access_token' => $accessToken]);

        // Redirect to a frontend route that handles the display logic.
        return redirect('/callback');
    }
    public function exchangeCodeForToken(Request $request)
    {
        $code = $request->input('code'); // Retrieve 'code' from the request body

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            "scope" => "user-read-private user-read-email playlist-modify-private playlist-modify-public",
            'redirect_uri' => 'http://localhost:8000/callback',
            'client_id' => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            session(['spotify_access_token' => $tokenData['access_token']]);
            return response()->json(['access_token' => $tokenData['access_token']]);
        } else {
            return response()->json(['error' => 'Spotify token exchange failed'], 401);
        }
    }
    
    public function findSong(Request $request, SpotifyService $spotifyService)
    {
        $songName = $request->input('songName');
        $accessToken = session('spotify_access_token'); // Or however you're managing tokens

        if (!$songName) {
            return response()->json(['error' => 'Song name is required'], 400);
        }

        $songDetails = $spotifyService->findSongDetails($songName, $accessToken);

        if (!$songDetails) {
            return response()->json(['error' => 'Song not found'], 404);
        }

        return response()->json($songDetails);
    }

    public function getAccountDetails(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = session('spotify_access_token') ?? $request->header('Authorization');
        
        // Ensure the Authorization header is provided
        if (!$accessToken) {
            return response()->json(['error' => 'Access Token is required'], 401);
        }

        // Call the service to get account details
        $accountDetails = $spotifyService->fetchAccountDetails($accessToken);

        if ($accountDetails) {
            return response()->json($accountDetails);
        } else {
            return response()->json(['error' => 'Failed to fetch account details'], 404);
        }
    }

    public function getMostListenedTracks(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = session('spotify_access_token') ?? $request->bearerToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Access Token is required'], 401);
        }

        $limit = $request->query('limit', 4); // Default to 4 if not specified

        // Use the SpotifyService to fetch the tracks
        $tracks = $spotifyService->getMostListenedTracks($accessToken, $limit);

        return response()->json($tracks);
    }
    
    public function createPlaylist(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = session('spotify_access_token') ?? $request->bearerToken();
        if (!$accessToken) {
            return response()->json(['error' => 'Access Token is required'], 401);
        }

        $userId = $this->getUserId($accessToken);
        if (!$userId) {
            return response()->json(['error' => 'Unable to fetch user data'], 400);
        }

        $name = $request->input('name', 'New Playlist');
        $description = $request->input('description', '');
        $public = $request->input('public', false);
        $trackUrls = $request->input('tracks', []);

        $trackUris = array_map(function ($url) {
            $parts = parse_url($url);
            $path = $parts['path'];
            $trackId = basename($path);
            return "spotify:track:$trackId";
        }, $trackUrls);

        $playlist = $spotifyService->createPlaylist($userId, $name, $description, $public, $accessToken);
        if (isset($playlist['error'])) {
            return response()->json($playlist, 400);
        }

        if (!empty($trackUris)) {
            $addTracksResult = $spotifyService->addTracksToPlaylist($playlist['id'], $trackUris, $accessToken);
            if (isset($addTracksResult['error'])) {
                return response()->json($addTracksResult, 400);
            }
        }

        return response()->json([
            'message' => 'Playlist created and tracks added successfully',
            'playlist' => $playlist
        ]);
    }


    private function getUserId($accessToken)
    {
        $accountDetails = (new SpotifyService())->fetchAccountDetails($accessToken);
        return $accountDetails['id'] ?? null;
    }
}
