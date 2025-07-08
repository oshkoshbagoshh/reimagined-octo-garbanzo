<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PexelsService
{
    /**
     * The Pexels API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The base URL for the Pexels API.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.pexels.com/v1';

    /**
     * Create a new Pexels service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = config('services.pexels.api_key');
    }

    /**
     * Search for images on Pexels.
     *
     * @param string $query
     * @param int $perPage
     * @param int $page
     * @return array|null
     */
    public function searchImages(string $query, int $perPage = 10, int $page = 1): ?array
    {
        $cacheKey = "pexels_search_{$query}_{$perPage}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($query, $perPage, $page) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $this->apiKey,
                ])->get("{$this->baseUrl}/search", [
                    'query' => $query,
                    'per_page' => $perPage,
                    'page' => $page,
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::error('Pexels API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                
                return null;
            } catch (\Exception $e) {
                Log::error('Pexels API exception', [
                    'message' => $e->getMessage(),
                ]);
                
                return null;
            }
        });
    }

    /**
     * Get a random image for a track based on its genres, moods, or title.
     *
     * @param \App\Models\Track $track
     * @return string|null The URL of the image
     */
    public function getRandomImageForTrack($track): ?string
    {
        $cacheKey = "pexels_track_image_{$track->id}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($track) {
            // Try to find an image based on the track's genres
            if (!empty($track->genres)) {
                $genre = $track->genres[array_rand($track->genres)];
                $result = $this->searchImages($genre, 10, 1);
                
                if ($result && !empty($result['photos'])) {
                    $photo = $result['photos'][array_rand($result['photos'])];
                    return $photo['src']['medium'];
                }
            }
            
            // Try to find an image based on the track's moods
            if (!empty($track->moods)) {
                $mood = $track->moods[array_rand($track->moods)];
                $result = $this->searchImages($mood, 10, 1);
                
                if ($result && !empty($result['photos'])) {
                    $photo = $result['photos'][array_rand($result['photos'])];
                    return $photo['src']['medium'];
                }
            }
            
            // Fallback to the track title
            $result = $this->searchImages($track->title, 10, 1);
            
            if ($result && !empty($result['photos'])) {
                $photo = $result['photos'][array_rand($result['photos'])];
                return $photo['src']['medium'];
            }
            
            // Ultimate fallback - search for "music"
            $result = $this->searchImages('music', 10, 1);
            
            if ($result && !empty($result['photos'])) {
                $photo = $result['photos'][array_rand($result['photos'])];
                return $photo['src']['medium'];
            }
            
            return null;
        });
    }
}