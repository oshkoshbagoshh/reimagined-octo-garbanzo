<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $licenseTypes = [
            'Standard', 'Premium', 'Exclusive', 
            'Web', 'Broadcast', 'Film', 'Commercial'
        ];
        
        return [
            'cart_id' => Cart::factory(),
            'track_id' => Track::factory(),
            'license_type' => fake()->randomElement($licenseTypes),
            'price' => fake()->randomFloat(2, 50, 1000),
            'project_title' => fake()->sentence(4),
            'project_description' => fake()->paragraph(),
            'usage_description' => fake()->paragraph(),
            'territory' => fake()->randomElement(['worldwide', 'north_america', 'europe', 'asia', 'australia']),
            'duration' => fake()->randomElement([3, 6, 12, 24, 36]), // months
        ];
    }
}