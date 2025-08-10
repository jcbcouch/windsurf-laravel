<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles
        $roles = Role::all();
        
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($roles->where('slug', 'admin')->first()->id);

        // Create editor user
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $editor->roles()->attach($roles->where('slug', 'editor')->first()->id);

        // Create author user
        $author = User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $author->roles()->attach($roles->where('slug', 'author')->first()->id);

        // Create 10 random users with random roles
        User::factory(10)->create()->each(function ($user) use ($roles) {
            // Assign 1-2 random roles to each user
            $user->roles()->attach(
                $roles->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
