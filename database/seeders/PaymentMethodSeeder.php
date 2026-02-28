<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = ['Cash on Delivery', 'GCash', 'Credit Card'];
        foreach ($methods as $name) {
            PaymentMethod::firstOrCreate(['name' => $name]);
        }
    }
}
