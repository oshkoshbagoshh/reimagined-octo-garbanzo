<?php

namespace App\Http\Controllers;

use App\Mail\ArtistDemoCampaign;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ArtistDemoCampaignController extends Controller
{
    /**
     * Show the form for creating a new demo campaign.
     */
    public function create(): Response
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            abort(404);
        }

        // Get the artist's tracks and upcoming shows for selection
        $artist->load([
            'tracks' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'shows' => function ($query) {
                $query->where('date', '>=', now())
                      ->orderBy('date', 'asc');
            }
        ]);

        return Inertia::render('Artist/DemoCampaign/Create', [
            'artist' => $artist,
            'tracks' => $artist->tracks,
            'shows' => $artist->shows,
        ]);
    }

    /**
     * Send a demo campaign email.
     */
    public function send(Request $request)
    {
        $artist = Auth::user()->artist;

        if (!$artist) {
            abort(404);
        }

        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'featured_track_ids' => 'nullable|array',
            'featured_track_ids.*' => 'exists:tracks,id',
            'upcoming_show_ids' => 'nullable|array',
            'upcoming_show_ids.*' => 'exists:shows,id',
        ]);

        // Get the selected tracks and shows
        $featuredTracks = [];
        if (!empty($validated['featured_track_ids'])) {
            $featuredTracks = $artist->tracks()
                ->whereIn('id', $validated['featured_track_ids'])
                ->get();
        }

        $upcomingShows = [];
        if (!empty($validated['upcoming_show_ids'])) {
            $upcomingShows = $artist->shows()
                ->whereIn('id', $validated['upcoming_show_ids'])
                ->get();
        }

        // Create the mailable
        $mailable = new ArtistDemoCampaign(
            $artist,
            $validated['subject'],
            $validated['message'],
            $featuredTracks->toArray(),
            $upcomingShows->toArray()
        );

        // Send the email to each recipient
        foreach ($validated['recipients'] as $recipient) {
            Mail::to($recipient)->queue($mailable);
        }

        return to_route('artist.demo-campaigns.create')
            ->with('success', 'Demo campaign emails have been queued for delivery.');
    }
}