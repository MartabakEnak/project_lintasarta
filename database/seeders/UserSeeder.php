<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'region' => null,
        ]);

        // Create Regional Admins
        $regionalAdmins = [
            [
                'name' => 'Admin Bali',
                'email' => 'bali@example.com',
                'region' => 'Bali'
            ],
            [
                'name' => 'Admin Nusa Tenggara Barat',
                'email' => 'ntb@example.com',
                'region' => 'Nusa Tenggara Barat'
            ],
            [
                'name' => 'Admin Nusa Tenggara Timur',
                'email' => 'ntt@example.com',
                'region' => 'Nusa Tenggara Timur'
            ]


        ];

        foreach ($regionalAdmins as $admin) {
            User::create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => Hash::make('password'),
                'role' => 'regional',
                'region' => $admin['region'],
            ]);
        }
    }
}