<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('middle_initial', 1)->nullable()->after('first_name');
            $table->string('last_name')->after('middle_initial');
        });

        // Migrate existing name data
        \DB::table('users')->get()->each(function ($user) {
            $nameParts = explode(' ', $user->name);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[count($nameParts) - 1] ?? '';
            $middleInitial = null;

            if (count($nameParts) > 2) {
                $middleInitial = strtoupper(substr($nameParts[1], 0, 1));
            }

            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'middle_initial' => $middleInitial,
                    'last_name' => $lastName,
                ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
        });

        // Combine name fields back to single name field
        \DB::table('users')->get()->each(function ($user) {
            $fullName = $user->first_name;
            if ($user->middle_initial) {
                $fullName .= ' ' . $user->middle_initial . '.';
            }
            $fullName .= ' ' . $user->last_name;

            \DB::table('users')
                ->where('id', $user->id)
                ->update(['name' => trim($fullName)]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_initial', 'last_name']);
        });
    }
};
