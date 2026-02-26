<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleAndUserStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('roles')->insert([
            ['name' => 'customer', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'admin', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('user_statuses')->insert([
            ['name' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'inactive', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
