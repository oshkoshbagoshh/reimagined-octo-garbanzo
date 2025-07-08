<?php

test('artist signup screen can be rendered', function () {
    $response = $this->get(route('artist.signup'));

    $response->assertStatus(200);
});

test('new artists can register', function () {
    $response = $this->withoutMiddleware()->post(route('artist.signup'), [
        'name' => 'Test User',
        'email' => 'artist@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'artist_name' => 'Test Artist',
        'bio' => 'This is a test artist bio',
        'website' => 'https://testartist.com',
        'social_links' => [
            'facebook' => 'https://facebook.com/testartist',
            'twitter' => 'https://twitter.com/testartist',
            'instagram' => 'https://instagram.com/testartist',
            'youtube' => 'https://youtube.com/testartist',
            'spotify' => 'https://spotify.com/artist/testartist',
            'soundcloud' => 'https://soundcloud.com/testartist',
        ],
        'genres' => ['rock', 'pop'],
    ]);

    // Check that the artist was created with the correct data
    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'artist@example.com',
    ]);

    $this->assertDatabaseHas('artists', [
        'name' => 'Test Artist',
        'bio' => 'This is a test artist bio',
        'website' => 'https://testartist.com',
    ]);

    $response->assertRedirect(route('artist.profile', absolute: false));
});

test('artist registration validates required fields', function () {
    $response = $this->withoutMiddleware()->post(route('artist.signup'), []);

    $response->assertInvalid(['name', 'email', 'password', 'artist_name']);
});

test('artist registration validates email uniqueness', function () {
    // Create a user with the email we'll try to register with
    \App\Models\User::factory()->create([
        'email' => 'duplicate@example.com',
    ]);

    $response = $this->withoutMiddleware()->post(route('artist.signup'), [
        'name' => 'Test User',
        'email' => 'duplicate@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'artist_name' => 'Test Artist',
    ]);

    $response->assertInvalid(['email']);
});

test('artist registration validates password confirmation', function () {
    $response = $this->withoutMiddleware()->post(route('artist.signup'), [
        'name' => 'Test User',
        'email' => 'artist@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
        'artist_name' => 'Test Artist',
    ]);

    $response->assertInvalid(['password']);
});
