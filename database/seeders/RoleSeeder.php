<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrator with full access',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can create and edit posts',
            ],
            [
                'name' => 'Author',
                'slug' => 'author',
                'description' => 'Can create and manage own posts',
            ],
            [
                'name' => 'Subscriber',
                'slug' => 'subscriber',
                'description' => 'Basic user with limited access',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
