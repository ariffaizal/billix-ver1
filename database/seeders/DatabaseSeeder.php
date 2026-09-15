<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin@billix.test'], // Kunci pencarian unik berdasarkan username
            [
                'name' => 'Super Admin',
                'email' => 'admin@billix.com',
                'username' => 'admin@billix.test',
                'password' => Hash::make('password123'),
                'role' => 'owner', // Diubah ke 'owner' agar sesuai dengan if di dashboard
                'is_protected' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}