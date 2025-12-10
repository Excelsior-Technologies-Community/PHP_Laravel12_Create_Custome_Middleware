<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SeedTestUsers extends Command
{
    protected $signature = 'seed:test-users';
    protected $description = 'Seed test users for middleware demo';

    public function handle(): void
    {
        $this->info('Seeding test users...');
        
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'last_activity_at' => now()
            ],
            [
                'name' => 'Moderator User',
                'email' => 'moderator@example.com',
                'password' => Hash::make('password'),
                'role' => 'moderator',
                'last_activity_at' => now()->subHours(2)
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'last_activity_at' => now()->subDays(1)
            ],
            [
                'name' => 'Inactive User',
                'email' => 'inactive@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'last_activity_at' => now()->subDays(40)
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'last_activity_at' => now()->subHours(5)
            ]
        ];
        
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
        
        $this->info('Test users seeded successfully!');
        $this->table(['Email', 'Password', 'Role'], [
            ['admin@example.com', 'password', 'admin'],
            ['moderator@example.com', 'password', 'moderator'],
            ['user@example.com', 'password', 'user'],
            ['inactive@example.com', 'password', 'user'],
            ['editor@example.com', 'password', 'user'],
        ]);
    }
}