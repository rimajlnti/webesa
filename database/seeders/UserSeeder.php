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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@esa.com',
            'password' => Hash::make('password'), // Pastikan password di-hash
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Sales User',
            'email' => 'sales@esa.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        User::create([
            'name' => 'IT User',
            'email' => 'it@esa.com',
            'password' => Hash::make('password'),
            'role' => 'it',
        ]);

        User::create([
            'name' => 'Director User',
            'email' => 'director@esa.com',
            'password' => Hash::make('password'),
            'role' => 'director',
        ]);
    }
}
