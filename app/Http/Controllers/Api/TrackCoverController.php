<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\JsonResponse;

class TrackCoverController extends Controller
{
    /**
     * Get the cover URL for a track.
     *
     * @param Track $track
     * @return JsonResponse
     */
    public function show(Track $track): JsonResponse
    {
        return response()->json([
            'cover_url' => $track->getCoverUrl('medium'),
        ]);
    }
}