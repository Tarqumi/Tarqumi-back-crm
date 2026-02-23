<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin — first admin account seeded in the database (per meeting_notes.md)
        User::firstOrCreate(
            ['email' => 'admin@tarqumi.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]
        );

        // CTO Founder — the ONLY founder role that can edit the landing page
        User::firstOrCreate(
            ['email' => 'cto@tarqumi.com'],
            [
                'name' => 'CTO Founder',
                'password' => Hash::make('password'),
                'role' => 'founder',
                'founder_role' => 'cto',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]
        );

        // CEO Founder
        User::firstOrCreate(
            ['email' => 'ceo@tarqumi.com'],
            [
                'name' => 'CEO Founder',
                'password' => Hash::make('password'),
                'role' => 'founder',
                'founder_role' => 'ceo',
                'is_active' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]
        );
    }
}
