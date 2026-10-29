<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Single admin user. Username/password can be changed later from /admin.
        User::updateOrCreate(
            ['username' => 'ksenija'],
            [
                'name' => 'Ksenija',
                'email' => 'ksenija@lumiskinpro.local',
                'password' => 'Ksenija.123', // hashed via User $casts
            ]
        );
    }
}
