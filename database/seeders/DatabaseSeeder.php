<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com', // Kamu bisa ganti email ini
            'password' => Hash::make('admin123'), // Kamu bisa ganti sandinya
            'role' => 'admin', // Penanda bahwa ini adalah Super Admin
        ]);
    }
}