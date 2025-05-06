<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'CyberOlympus',
            'email' => 'admin@cyberolympus.com',
            'password' => Hash::make('cyberadmin'),
            'account_role' => 'admin',
            'account_status' => 'active',
            'phone' => '0000000000', // sesuaikan jika diperlukan
            'email_verified_at' => now(),
        ]);
    }
} 