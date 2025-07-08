<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Rock', 'Pop', 'Hip Hop', 'Electronic', 'Jazz', 'Classical', 'R&B', 'Country', 'Folk', 'Metal'];
        
        return [
            'name' => fake()->name(),
            'bio' => fake()->paragraph(),
            'profile_image' => 'artists/' . fake()->uuid() . '.jpg',
            'website' => fake()->url(),
            'social_links' => [
                'instagram' => 'https://instagram.com/' . fake()->userName(),
                'twitter' => 'https://twitter.com/' . fake()->userName(),
                'facebook' => 'https://facebook.com/' . fake()->userName(),
                'spotify' => 'https://open.spotify.com/artist/' . fake()->uuid(),
            ],
            'genres' => fake()->randomElements($genres, fake()->numberBetween(1, 3)),
            'user_id' => User::factory(),
        ];
    }
}