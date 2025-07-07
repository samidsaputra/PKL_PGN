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
    public function run(): void
    {
        $userData= [
            [
                'id' => 'CSS001', // UUID sebagai primary key
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Password default
                'role' => 'admin',
                'satuan_kerja' => 'Corporate Support & Services', // Atur sesuai kebutuhan, bisa null
            ],
            [
                'id' => 'CSS002', // UUID sebagai primary key
                'name' => 'Requester',
                'email' => 'requester@example.com',
                'password' => Hash::make('password'), // Password default
                'role' => 'requester',
                'satuan_kerja' => 'Corporate Support & Services',
            ],
            [
                'id' => 'CSS003', // UUID sebagai primary key
                'name' => 'Approver',
                'email' => 'approver@example.com',
                'password' => Hash::make('password'), // Password default
                'role' => 'approver',
                'satuan_kerja' => 'Corporate Support & Services',
            ],
        ];
        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}
