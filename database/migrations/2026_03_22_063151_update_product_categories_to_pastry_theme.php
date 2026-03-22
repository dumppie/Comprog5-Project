<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing products with old categories to new pastry categories
        $categoryMapping = [
            'electronics' => 'pastries',
            'clothing' => 'buns',
            'food' => 'bread',
            'books' => 'cookies',
            'toys' => 'muffins',
            'sports' => 'croissants',
            'home' => 'cakes',
            'beauty' => 'tarts',
            'automotive' => 'pies',
            'other' => 'pastries'
        ];

        foreach ($categoryMapping as $oldCategory => $newCategory) {
            DB::table('products')
                ->where('category', $oldCategory)
                ->update(['category' => $newCategory]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old categories
        $reverseMapping = [
            'pastries' => 'electronics',
            'buns' => 'clothing',
            'bread' => 'food',
            'cookies' => 'books',
            'muffins' => 'toys',
            'croissants' => 'sports',
            'cakes' => 'home',
            'tarts' => 'beauty',
            'pies' => 'automotive'
        ];

        foreach ($reverseMapping as $newCategory => $oldCategory) {
            DB::table('products')
                ->where('category', $newCategory)
                ->update(['category' => $oldCategory]);
        }
    }
};
