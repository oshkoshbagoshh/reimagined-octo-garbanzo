<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\JsonResponse;

class ArtistController extends Controller
{
    /**
     * Get a list of all artists.
     */
    public function index(): JsonResponse
    {
        $artists = Artist::select('id', 'name')->orderBy('name')->get();
        
        return response()->json($artists);
    }
}