<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            // If no users exist, create one first
            $user = User::factory()->create();
            // Create posts for this user
            Post::factory(10)->create([
                'user_id' => $user->id
            ]);
        } else {
            // Create 3-5 posts for each user
            $users->each(function ($user) {
                Post::factory(rand(3, 5))->create([
                    'user_id' => $user->id
                ]);
            });
        }
    }
}
