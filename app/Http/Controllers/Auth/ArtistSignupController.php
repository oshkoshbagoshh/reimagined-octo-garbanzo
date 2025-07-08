<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class ArtistSignupController extends Controller
{
    /**
     * Show the artist registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/ArtistSignup');
    }

    /**
     * Handle an incoming artist registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'artist_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'genres' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create the artist profile
        $artist = Artist::create([
            'name' => $request->artist_name,
            'bio' => $request->bio,
            'website' => $request->website,
            'social_links' => $request->social_links,
            'genres' => $request->genres,
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('artist.profile');
    }
}