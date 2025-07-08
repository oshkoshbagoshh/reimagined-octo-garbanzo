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

// API routes
Route::prefix('api')->group(function () {
    Route::get('artists', [App\Http\Controllers\Api\ArtistController::class, 'index']);
    Route::get('tracks/{track}/cover', [App\Http\Controllers\Api\TrackCoverController::class, 'show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
