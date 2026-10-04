<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Irul',
            'email' => 'khairyul@email.com',
            'password' => Hash::make('password'),
            'plan_label' => 'Learner Pro Aktif',
            'avatar_url' => 'assets/images/avatar_ammsy.png',
        ]);
    }
}