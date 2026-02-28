<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['Pending', 'Processing', 'Ready', 'Completed', 'Cancelled'];
        foreach ($statuses as $name) {
            OrderStatus::firstOrCreate(['name' => $name]);
        }
    }
}
