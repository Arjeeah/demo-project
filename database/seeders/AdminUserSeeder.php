<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $adminUsers = [
            [
                'name'     => 'Admin One',
                'email'    => 'admin1@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name'     => 'Admin Two',
                'email'    => 'admin2@example.com',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($adminUsers as $data) {
            // Create the user if they don't exist already.
            $user = User::firstOrCreate(['email' => $data['email']], $data);
            // Assign the 'admin' role to the user.
            $user->assignRole('admin');
        }
    }
}
