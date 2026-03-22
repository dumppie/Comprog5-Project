<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserStatusSeeder::class,
            AdminUserSeeder::class,
            OrderStatusSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
