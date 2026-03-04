<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('user_statuses')->insert([
            ['name' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'inactive', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
