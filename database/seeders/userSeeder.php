<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Nette\Utils\Random;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Requester User',
            'email' => 'requester@example.com',
            'password' => Hash::make('password'),
            'role' => 'Requester',
        ]);

        User::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Approver User',
            'email' => 'approver@example.com',
            'password' => Hash::make('password'),
            'role' => 'Approver',
        ]);
    }
}
