<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LicenseRequest>
 */
class LicenseRequestFactory extends Factory
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
        
        $statuses = ['pending', 'approved', 'rejected', 'completed'];
        
        return [
            'status' => fake()->randomElement($statuses),
            'license_type' => fake()->randomElement($licenseTypes),
            'project_title' => fake()->sentence(4),
            'project_description' => fake()->paragraph(),
            'usage_description' => fake()->paragraph(),
            'territory' => fake()->randomElement(['worldwide', 'north_america', 'europe', 'asia', 'australia']),
            'duration' => fake()->randomElement([3, 6, 12, 24, 36]), // months
            'price' => fake()->randomFloat(2, 50, 1000),
            'license_document' => fake()->randomElement([null, 'licenses/' . fake()->uuid() . '.pdf']),
            'track_id' => Track::factory(),
            'brand_id' => Brand::factory(),
            'user_id' => User::factory(),
        ];
    }
    
    /**
     * Indicate that the license request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'license_document' => null,
        ]);
    }
    
    /**
     * Indicate that the license request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'license_document' => 'licenses/' . fake()->uuid() . '.pdf',
        ]);
    }
    
    /**
     * Indicate that the license request is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'license_document' => 'licenses/' . fake()->uuid() . '.pdf',
        ]);
    }
}