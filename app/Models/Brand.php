<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'industry',
        'user_id',
    ];

    /**
     * Get the license requests for the brand.
     */
    public function licenseRequests(): HasMany
    {
        return $this->hasMany(LicenseRequest::class);
    }

    /**
     * Get the user that owns the brand.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}