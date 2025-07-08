<?php

namespace Database\Factories;

use App\Models\LicenseRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollaborationMessage>
 */
class CollaborationMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->paragraph(),
            'attachment' => fake()->randomElement([null, 'attachments/' . fake()->uuid() . '.pdf']),
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'license_request_id' => LicenseRequest::factory(),
            'read_at' => fake()->randomElement([null, fake()->dateTimeBetween('-1 week', 'now')]),
        ];
    }
    
    /**
     * Indicate that the message has been read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
    
    /**
     * Indicate that the message has not been read.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }
    
    /**
     * Indicate that the message has an attachment.
     */
    public function withAttachment(): static
    {
        return $this->state(fn (array $attributes) => [
            'attachment' => 'attachments/' . fake()->uuid() . '.pdf',
        ]);
    }
}