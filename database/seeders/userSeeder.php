<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'John Doe',
                'email' => 'john@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Requester',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'Approver',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
