<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Show;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ArtistProfileController extends Controller
{
    /**
     * Display the artist's profile.
     */
    public function show(Artist $artist = null): Response
    {
        // If no artist is specified, use the authenticated user's artist
        if (!$artist && Auth::check() && Auth::user()->artist) {
            $artist = Auth::user()->artist;
        }

        if (!$artist) {
            abort(404);
        }

        // Load the artist's tracks, shows, and other related data
        $artist->load([
            'tracks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'shows' => function ($query) {
                $query->where('date', '>=', now())
                      ->orderBy('date', 'asc');
            }
        ]);

        return Inertia::render('Artist/Profile', [
            'artist' => $artist,
            'tracks' => $artist->tracks,
            'shows' => $artist->shows,
            'isOwner' => Auth::check() && Auth::id() === $artist->user_id,
        ]);
    }

    /**
     * Show the form for editing the artist's profile.
     */
    public function edit(): Response
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            return to_route('artist.signup');
        }

        return Inertia::render('Artist/Edit', [
            'artist' => $artist,
        ]);
    }

    /**
     * Update the artist's profile.
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'genres' => 'nullable|array',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;

            // Delete old image if it exists
            if ($artist->profile_image) {
                Storage::disk('public')->delete($artist->profile_image);
            }
        }

        $artist->update($validated);

        return to_route('artist.profile');
    }

    /**
     * Show the form for creating a new show.
     */
    public function createShow(): Response
    {
        return Inertia::render('Artist/Shows/Create');
    }

    /**
     * Store a newly created show.
     */
    public function storeShow(Request $request): \Illuminate\Http\RedirectResponse
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'ticket_url' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
        ]);

        $artist->shows()->create($validated);

        return to_route('artist.profile');
    }
}