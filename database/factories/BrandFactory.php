<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $industries = [
            'Advertising', 'Film & TV', 'Gaming', 'Social Media', 
            'Fashion', 'Technology', 'Food & Beverage', 'Automotive',
            'Healthcare', 'Education', 'Entertainment', 'Sports'
        ];
        
        return [
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'logo' => 'brands/' . fake()->uuid() . '.jpg',
            'website' => fake()->url(),
            'industry' => fake()->randomElement($industries),
            'user_id' => User::factory(),
        ];
    }
}