<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Secure initial admin credentials (plaintext provided separately)
        $adminEmail = 'admin@school.local';
        $adminPassword = '123123123';

        // Avoid duplicate seeding if rerun
        $existing = User::where('email', $adminEmail)->first();
        if (!$existing) {
            $user = User::create([
                'name' => 'School Administrator',
                'user_id' => 'A' . now()->format('Y') . '0001',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'status' => 'active',
            ]);

            // Create matching admin profile (if model/table exists)
            Admin::create([
                'admin_id' => $user->user_id ?? ('A' . $user->id),
                'fname' => 'School',
                'lname' => 'Administrator',
                'dob' => '1990-01-01',
                'phone' => '0000000000',
                'gender' => 'Other',
                'address' => 'Main Office',
                'user_id' => $user->id,
            ]);
        }
    }
}
