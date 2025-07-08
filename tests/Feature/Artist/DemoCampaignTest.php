<?php

use App\Models\Artist;
use App\Models\Track;
use App\Models\Show;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ArtistDemoCampaign;

beforeEach(function () {
    Mail::fake();

    // Create a user with an artist profile
    $user = User::factory()->create();
    $this->artist = Artist::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create some tracks for the artist
    $this->tracks = Track::factory()->count(3)->create([
        'artist_id' => $this->artist->id,
    ]);

    // Create some shows for the artist
    $this->shows = Show::factory()->count(2)->create([
        'artist_id' => $this->artist->id,
        'date' => now()->addDays(10),
    ]);

    // Act as the authenticated user
    $this->actingAs($user);
});

test('demo campaign create screen can be rendered', function () {
    $response = $this->get(route('artist.demo-campaigns.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Artist/DemoCampaign/Create')
        ->has('artist')
        ->has('tracks', 3)
        ->has('shows', 2)
    );
});

test('artist can send a demo campaign', function () {
    $response = $this->withoutMiddleware()->post(route('artist.demo-campaigns.send'), [
        'recipients' => ['test@example.com', 'another@example.com'],
        'subject' => 'Check out my new music',
        'message' => 'Here are some tracks I wanted to share with you.',
        'featured_track_ids' => [$this->tracks[0]->id, $this->tracks[1]->id],
        'upcoming_show_ids' => [$this->shows[0]->id],
    ]);

    $response->assertRedirect(route('artist.demo-campaigns.create'));
    $response->assertSessionHas('success');

    // Assert that emails were queued
    Mail::assertQueued(ArtistDemoCampaign::class, 2);

    // Assert that the email contains the correct data
    Mail::assertQueued(ArtistDemoCampaign::class, function ($mail) {
        return $mail->hasTo('test@example.com') &&
               $mail->subject === 'Check out my new music' &&
               count($mail->featuredTracks) === 2 &&
               count($mail->upcomingShows) === 1;
    });
});

test('demo campaign validates required fields', function () {
    $response = $this->withoutMiddleware()->post(route('artist.demo-campaigns.send'), []);

    $response->assertInvalid(['recipients', 'subject', 'message']);
});

test('demo campaign validates email format', function () {
    $response = $this->withoutMiddleware()->post(route('artist.demo-campaigns.send'), [
        'recipients' => ['not-an-email', 'another@example.com'],
        'subject' => 'Check out my new music',
        'message' => 'Here are some tracks I wanted to share with you.',
    ]);

    $response->assertInvalid(['recipients.0']);
});

test('demo campaign validates track existence', function () {
    $response = $this->withoutMiddleware()->post(route('artist.demo-campaigns.send'), [
        'recipients' => ['test@example.com'],
        'subject' => 'Check out my new music',
        'message' => 'Here are some tracks I wanted to share with you.',
        'featured_track_ids' => [999], // Non-existent track ID
    ]);

    $response->assertInvalid(['featured_track_ids.0']);
});

test('demo campaign validates show existence', function () {
    $response = $this->withoutMiddleware()->post(route('artist.demo-campaigns.send'), [
        'recipients' => ['test@example.com'],
        'subject' => 'Check out my new music',
        'message' => 'Here are some tracks I wanted to share with you.',
        'upcoming_show_ids' => [999], // Non-existent show ID
    ]);

    $response->assertInvalid(['upcoming_show_ids.0']);
});
