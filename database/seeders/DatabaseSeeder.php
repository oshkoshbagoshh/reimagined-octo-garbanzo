<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CollaborationMessage;
use App\Models\LicenseRequest;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create artists with tracks
        $artists = Artist::factory(10)->create();

        foreach ($artists as $artist) {
            Track::factory(rand(3, 8))->create([
                'artist_id' => $artist->id,
            ]);
        }

        // Create brands
        $brands = Brand::factory(5)->create();

        // Create license requests
        foreach ($brands as $brand) {
            $tracks = Track::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($tracks as $track) {
                LicenseRequest::factory()->create([
                    'track_id' => $track->id,
                    'brand_id' => $brand->id,
                    'user_id' => $brand->user_id,
                ]);
            }
        }

        // Create carts with items
        $users = User::all();

        foreach ($users as $user) {
            $cart = Cart::factory()->create([
                'user_id' => $user->id,
            ]);

            $tracks = Track::inRandomOrder()->take(rand(1, 5))->get();

            foreach ($tracks as $track) {
                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                    'track_id' => $track->id,
                ]);
            }
        }

        // Create collaboration messages
        $licenseRequests = LicenseRequest::all();

        foreach ($licenseRequests as $licenseRequest) {
            $artistUser = $licenseRequest->track->artist->user;
            $brandUser = $licenseRequest->brand->user;

            // Messages from brand to artist
            CollaborationMessage::factory(rand(1, 3))->create([
                'sender_id' => $brandUser->id,
                'receiver_id' => $artistUser->id,
                'license_request_id' => $licenseRequest->id,
            ]);

            // Messages from artist to brand
            CollaborationMessage::factory(rand(1, 3))->create([
                'sender_id' => $artistUser->id,
                'receiver_id' => $brandUser->id,
                'license_request_id' => $licenseRequest->id,
            ]);
        }
    }
}
