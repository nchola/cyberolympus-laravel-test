<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@cyberolympus.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'CyberOlympus',
                'password' => Hash::make('cyberadmin'),
                'account_role' => 'admin',
                'account_status' => 'active',
                'phone' => '08123456789',
                'account_type' => 1,
                'email_verified_at' => now(),
            ]
        );
    }
} 