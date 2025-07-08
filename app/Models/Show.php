<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Show extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'artist_id',
        'title',
        'description',
        'date',
        'venue',
        'city',
        'country',
        'ticket_url',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the artist that owns the show.
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}