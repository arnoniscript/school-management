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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('senha123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('senha123'),
            'role' => 'student',
        ]);

    }
}
