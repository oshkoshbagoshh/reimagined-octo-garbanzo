<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Track extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'duration',
        'file_path',
        'waveform_path',
        'cover_image',
        'bpm',
        'key',
        'genres',
        'moods',
        'instruments',
        'price',
        'artist_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'duration' => 'integer',
        'bpm' => 'integer',
        'price' => 'decimal:2',
        'genres' => 'array',
        'moods' => 'array',
        'instruments' => 'array',
    ];

    /**
     * Get the artist that owns the track.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Get the license requests for the track.
     */
    public function licenseRequests(): HasMany
    {
        return $this->hasMany(LicenseRequest::class);
    }

    /**
     * Get the cart items for the track.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}