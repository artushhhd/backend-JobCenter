<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const PASSWORD = 'password123';

    private const USERS = [
        [
            'name' => 'Alex Morgan',
            'email' => 'alex.morgan@example.com',
            'status' => 'job_seeker',
            'role' => 'user',
        ],
        [
            'name' => 'Mira Kavanagh',
            'email' => 'mira.kavanagh@example.com',
            'status' => 'job_seeker',
            'role' => 'user',
        ],
        [
            'name' => 'Olivia Park',
            'email' => 'olivia.park@example.com',
            'status' => 'job_poster',
            'role' => 'user',
        ],
        [
            'name' => 'Daniel Reyes',
            'email' => 'daniel.reyes@example.com',
            'status' => 'job_poster',
            'role' => 'moderator',
        ],
        [
            'name' => 'Hana Ito',
            'email' => 'hana.ito@example.com',
            'status' => 'job_poster',
            'role' => 'admin',
        ],
        [
            'name' => 'Rhea Kallias',
            'email' => 'rhea.kallias@example.com',
            'status' => 'job_poster',
            'role' => 'super_admin',
        ],
    ];

    public function run(): void
    {
        foreach (self::USERS as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make(self::PASSWORD),
                    'status' => $userData['status'],
                    'role' => $userData['role'],
                ]
            );
        }
    }
}
