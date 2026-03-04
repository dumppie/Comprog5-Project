<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have the active status
        $activeStatusId = UserStatus::where('name', 'active')->value('id');

        if (!$activeStatusId) {
            return;
        }

        // Admin 1: Mervin
        User::updateOrCreate(
            ['email' => 'mervin@gmail.com'],
            [
                'first_name' => 'Mervin',
                'middle_name' => null,
                'last_name' => 'Admin',
                'password' => Hash::make('admin1'),
                'is_admin' => true,
                'user_status_id' => $activeStatusId,
                'email_verified_at' => now(),
            ]
        );

        // Admin 2: Miguel
        User::updateOrCreate(
            ['email' => 'miguel@gmail.com'],
            [
                'first_name' => 'Miguel',
                'middle_name' => null,
                'last_name' => 'Admin',
                'password' => Hash::make('admin2'),
                'is_admin' => true,
                'user_status_id' => $activeStatusId,
                'email_verified_at' => now(),
            ]
        );
    }
}
