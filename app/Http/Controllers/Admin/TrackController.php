<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TrackController extends Controller
{
    /**
     * Display a listing of the tracks.
     */
    public function index()
    {
        $tracks = Track::with('artist')->latest()->paginate(10);

        return Inertia::render('Admin/Tracks/Index', [
            'tracks' => $tracks,
        ]);
    }

    /**
     * Show the form for creating a new track.
     */
    public function create()
    {
        return Inertia::render('Admin/Tracks/Create');
    }

    /**
     * Store a newly created track in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'file' => 'required|file|mimes:mp3,wav,ogg|max:50000',
            'cover_image' => 'nullable|image|max:2048',
            'bpm' => 'nullable|integer',
            'key' => 'nullable|string|max:10',
            'genres' => 'nullable|array',
            'moods' => 'nullable|array',
            'instruments' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'artist_id' => 'required|exists:artists,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tracks', 'public');
            $validated['file_path'] = $filePath;
        }

        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $imagePath;
        }

        // Remove the file field as it's not in the database
        unset($validated['file']);

        Track::create($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track created successfully.');
    }

    /**
     * Show the form for editing the specified track.
     */
    public function edit(Track $track)
    {
        return Inertia::render('Admin/Tracks/Edit', [
            'track' => $track->load('artist'),
        ]);
    }

    /**
     * Update the specified track in storage.
     */
    public function update(Request $request, Track $track)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'file' => 'nullable|file|mimes:mp3,wav,ogg|max:50000',
            'cover_image' => 'nullable|image|max:2048',
            'bpm' => 'nullable|integer',
            'key' => 'nullable|string|max:10',
            'genres' => 'nullable|array',
            'moods' => 'nullable|array',
            'instruments' => 'nullable|array',
            'price' => 'required|numeric|min:0',
            'artist_id' => 'required|exists:artists,id',
        ]);

        // Handle file uploads
        if ($request->hasFile('file')) {
            // Delete old file if it exists
            if ($track->file_path && Storage::disk('public')->exists($track->file_path)) {
                Storage::disk('public')->delete($track->file_path);
            }
            
            $filePath = $request->file('file')->store('tracks', 'public');
            $validated['file_path'] = $filePath;
        }

        if ($request->hasFile('cover_image')) {
            // Delete old image if it exists
            if ($track->cover_image && Storage::disk('public')->exists($track->cover_image)) {
                Storage::disk('public')->delete($track->cover_image);
            }
            
            $imagePath = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $imagePath;
        }

        // Remove the file field as it's not in the database
        unset($validated['file']);

        $track->update($validated);

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track updated successfully.');
    }

    /**
     * Remove the specified track from storage.
     */
    public function destroy(Track $track)
    {
        // Delete associated files
        if ($track->file_path && Storage::disk('public')->exists($track->file_path)) {
            Storage::disk('public')->delete($track->file_path);
        }
        
        if ($track->cover_image && Storage::disk('public')->exists($track->cover_image)) {
            Storage::disk('public')->delete($track->cover_image);
        }
        
        if ($track->waveform_path && Storage::disk('public')->exists($track->waveform_path)) {
            Storage::disk('public')->delete($track->waveform_path);
        }

        $track->delete();

        return redirect()->route('admin.tracks.index')
            ->with('success', 'Track deleted successfully.');
    }
}