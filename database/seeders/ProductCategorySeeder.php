<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        
        // Get categories from config
        $categories = config('categories.product_categories');
        
        // Convert to array format for seeding
        $categoryData = [];
        foreach ($categories as $name => $description) {
            $categoryData[] = [
                'name' => $name,
                'description' => $description,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Check if categories table exists, if not create it
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Insert categories if they don't exist
        foreach ($categoryData as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('Product categories seeded successfully!');
    }
}
