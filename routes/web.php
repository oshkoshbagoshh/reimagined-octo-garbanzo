<?php

use App\Http\Controllers\TrackController;
use App\Http\Controllers\Admin\TrackController as AdminTrackController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [TrackController::class, 'index'])->name('home');

Route::get('tracks/{track}', [TrackController::class, 'show'])->name('tracks.show');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Track management
    Route::resource('tracks', AdminTrackController::class);
});

// Artist routes
Route::prefix('artist')->name('artist.')->group(function () {
    Route::get('signup', [App\Http\Controllers\Auth\ArtistSignupController::class, 'create'])
        ->middleware('guest')
        ->name('signup');
    Route::post('signup', [App\Http\Controllers\Auth\ArtistSignupController::class, 'store'])
        ->middleware('guest');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('profile', [App\Http\Controllers\ArtistProfileController::class, 'show'])
            ->name('profile');
        Route::get('profile/edit', [App\Http\Controllers\ArtistProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('profile', [App\Http\Controllers\ArtistProfileController::class, 'update'])
            ->name('profile.update');

        // Shows management
        Route::get('shows/create', [App\Http\Controllers\ArtistProfileController::class, 'createShow'])
            ->name('shows.create');
        Route::post('shows', [App\Http\Controllers\ArtistProfileController::class, 'storeShow'])
            ->name('shows.store');

        // Demo campaigns
        Route::get('demo-campaigns/create', [App\Http\Controllers\ArtistDemoCampaignController::class, 'create'])
            ->name('demo-campaigns.create');
        Route::post('demo-campaigns/send', [App\Http\Controllers\ArtistDemoCampaignController::class, 'send'])
            ->name('demo-campaigns.send');
    });
});

// Public artist profile
Route::get('artists/{artist}', [App\Http\Controllers\ArtistProfileController::class, 'show'])
    ->name('artists.show');

// API routes
Route::prefix('api')->group(function () {
    Route::get('artists', [App\Http\Controllers\Api\ArtistController::class, 'index']);
    Route::get('tracks/{track}/cover', [App\Http\Controllers\Api\TrackCoverController::class, 'show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
