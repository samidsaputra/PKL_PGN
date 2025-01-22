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
            'id' => 'CSS001', // UUID sebagai primary key
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('shaqi123'), // Password default
            'role' => 'admin',
            'satuan_kerja' => 'CSS', // Atur sesuai kebutuhan, bisa null
        ]);
    }
}
