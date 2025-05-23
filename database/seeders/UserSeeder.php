<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


public function run()
{
    User::create([
        'name' => 'Student Test',
        'email' => 'student@test.com',
        'password' => Hash::make('password'),
        'role' => 'student',
    ]);

    User::create([
        'name' => 'Teacher Test',
        'email' => 'teacher@test.com',
        'password' => Hash::make('password'),
        'role' => 'teacher',
    ]);

    User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
}

}
