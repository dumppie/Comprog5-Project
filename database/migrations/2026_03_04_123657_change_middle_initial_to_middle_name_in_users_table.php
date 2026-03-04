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
            $table->string('middle_name')->nullable()->after('first_name');
        });

        // Migrate existing middle initial data to middle name
        \DB::table('users')->whereNotNull('middle_initial')->get()->each(function ($user) {
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['middle_name' => $user->middle_initial]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('middle_initial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_initial', 1)->nullable()->after('first_name');
        });

        // Migrate middle name back to middle initial (take first character)
        \DB::table('users')->whereNotNull('middle_name')->get()->each(function ($user) {
            $middleInitial = strtoupper(substr($user->middle_name, 0, 1));
            \DB::table('users')
                ->where('id', $user->id)
                ->update(['middle_initial' => $middleInitial]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('middle_name');
        });
    }
};
