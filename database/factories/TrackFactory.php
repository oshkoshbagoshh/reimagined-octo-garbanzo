<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genres = ['Rock', 'Pop', 'Hip Hop', 'Electronic', 'Jazz', 'Classical', 'R&B', 'Country', 'Folk', 'Metal'];
        $moods = ['Happy', 'Sad', 'Energetic', 'Calm', 'Aggressive', 'Romantic', 'Mysterious', 'Epic', 'Playful', 'Melancholic'];
        $instruments = ['Guitar', 'Piano', 'Drums', 'Bass', 'Violin', 'Saxophone', 'Trumpet', 'Synthesizer', 'Vocals', 'Flute'];
        $keys = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
        
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(60, 300), // 1-5 minutes in seconds
            'file_path' => 'tracks/' . fake()->uuid() . '.mp3',
            'waveform_path' => 'waveforms/' . fake()->uuid() . '.json',
            'cover_image' => 'covers/' . fake()->uuid() . '.jpg',
            'bpm' => fake()->numberBetween(60, 180),
            'key' => fake()->randomElement($keys),
            'genres' => fake()->randomElements($genres, fake()->numberBetween(1, 3)),
            'moods' => fake()->randomElements($moods, fake()->numberBetween(1, 3)),
            'instruments' => fake()->randomElements($instruments, fake()->numberBetween(1, 5)),
            'price' => fake()->randomFloat(2, 50, 500),
            'artist_id' => Artist::factory(),
        ];
    }
}