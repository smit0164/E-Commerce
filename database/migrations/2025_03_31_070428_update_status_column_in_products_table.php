<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove the boolean status column
        });

        Schema::table('products', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active'); // Add enum status column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove enum status column
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('status')->default(true); // Revert to boolean status column
        });
    }
};
