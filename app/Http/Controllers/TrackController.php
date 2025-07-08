<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Display a listing of the tracks.
     */
    public function index()
    {
        $tracks = Track::with('artist')->latest()->get();

        return Inertia::render('Welcome', [
            'tracks' => $tracks,
        ]);
    }

    /**
     * Display the specified track.
     */
    public function show(Track $track)
    {
        $track->load('artist');

        return Inertia::render('Tracks/Show', [
            'track' => $track,
        ]);
    }
}