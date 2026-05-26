<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@bookly.com',
                'role'     => 'super_admin',
                'password' => $password,
            ],
            [
                'name'     => 'Dr. Sarah Chen',
                'email'    => 'sarah@bookly.com',
                'role'     => 'reviewer',
                'password' => $password,
            ],
            [
                'name'     => 'Prof. James Miller',
                'email'    => 'james@bookly.com',
                'role'     => 'reviewer',
                'password' => $password,
            ],
            [
                'name'     => 'Dr. Priya Sharma',
                'email'    => 'priya@bookly.com',
                'role'     => 'reviewer',
                'password' => $password,
            ],
            [
                'name'     => 'Alex Johnson',
                'email'    => 'alex@bookly.com',
                'role'     => 'viewer',
                'password' => $password,
            ],
            [
                'name'     => 'Maria Garcia',
                'email'    => 'maria@bookly.com',
                'role'     => 'viewer',
                'password' => $password,
            ],
        ];

        foreach ($users as $userData) {
            User::create(array_merge($userData, [
                'email_verified_at' => now(),
                'is_active'         => true,
            ]));
        }
    }
}
