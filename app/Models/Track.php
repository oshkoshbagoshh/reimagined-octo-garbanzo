<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\PexelsService;

class Track extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

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
     * Register media collections for the model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200);

                $this->addMediaConversion('medium')
                    ->width(400)
                    ->height(400);
            });
    }

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

    /**
     * Get the cover image URL with fallback to Pexels.
     *
     * @param string $conversion
     * @return string|null
     */
    public function getCoverUrl(string $conversion = ''): ?string
    {
        // Check if the track has a cover image
        if ($this->hasMedia('cover')) {
            return $conversion ? 
                $this->getFirstMediaUrl('cover', $conversion) : 
                $this->getFirstMediaUrl('cover');
        }

        // Fallback to Pexels
        $pexelsService = app(PexelsService::class);
        return $pexelsService->getRandomImageForTrack($this);
    }
}
