<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ayush Bohra',
            'email' => '2022.ayush.bohra@ves.ac.in',
            'password' => Hash::make('TestAdmin@2024!'), // Strong password for testing
            'email_verified_at' => now(),
            'role' => 'admin',
            'department' => 'Computer Engineering',
            'year' => 'TE',
            'division' => 'A',
            'roll_number' => '60',
            'bio' => 'Admin account for testing',
            'is_active' => true,
            'email_notifications' => true,
        ]);
    }
}
