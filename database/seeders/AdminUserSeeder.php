<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have the admin role and active status
        $adminRoleId = Role::where('name', 'admin')->value('id');
        $activeStatusId = UserStatus::where('name', 'active')->value('id');

        if (!$adminRoleId || !$activeStatusId) {
            return;
        }

        // Admin 1: Mervin
        User::updateOrCreate(
            ['email' => 'mervin@gmail.com'],
            [
                'name' => 'Mervin',
                'password' => Hash::make('admin1'),
                'role_id' => $adminRoleId,
                'user_status_id' => $activeStatusId,
                'email_verified_at' => now(),
            ]
        );

        // Admin 2: Miguel
        User::updateOrCreate(
            ['email' => 'miguel@gmail.com'],
            [
                'name' => 'Miguel',
                'password' => Hash::make('admin2'),
                'role_id' => $adminRoleId,
                'user_status_id' => $activeStatusId,
                'email_verified_at' => now(),
            ]
        );
    }
}
