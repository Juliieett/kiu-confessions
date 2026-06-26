<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'KIU Admin',
            'email'    => 'admin@kiu.edu.ge',
            'password' => 'password',
            'is_admin' => true,
        ]);

        User::create([
            'name'     => 'Demo Student',
            'email'    => 'student@kiu.edu.ge',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->command->info('✅ Seeded admin (admin@kiu.edu.ge) and student (student@kiu.edu.ge) — password: password');
    }
}
