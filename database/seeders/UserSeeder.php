<?php

namespace Database\Seeders;

use App\Enums\User\UserPosition;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'John Manager',
                'position' => UserPosition::MANAGER->value,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => 'Alice Developer',
                'position' => UserPosition::DEVELOPER->value,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'tester@example.com'],
            [
                'name' => 'Bob Tester',
                'position' => UserPosition::TESTER->value,
            ]
        );
    }
}
