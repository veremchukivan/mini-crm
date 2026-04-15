<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::query()->updateOrCreate(
            ['email' => 'manager@mini-crm.test'],
            [
                'name' => 'Test Manager',
                'password' => Hash::make('password'),
            ]
        );

        $manager->syncRoles(['manager']);
    }
}
