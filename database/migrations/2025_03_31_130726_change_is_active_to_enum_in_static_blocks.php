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
        Schema::table('static_blocks', function (Blueprint $table) {
            $table->dropColumn('is_active'); // Remove the boolean column
        });

        Schema::table('static_blocks', function (Blueprint $table) {
            $table->enum('is_active', ['active', 'inactive'])->default('active'); // Add ENUM column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('static_blocks', function (Blueprint $table) {
            $table->dropColumn('is_active'); // Remove ENUM column
        });

        Schema::table('static_blocks', function (Blueprint $table) {
            $table->boolean('is_active')->default(true); // Restore boolean column
        });
    }
};
