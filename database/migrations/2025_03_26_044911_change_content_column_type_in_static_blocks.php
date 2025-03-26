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
            $table->longText('content')->change();
        });
    }

    public function down(): void
    {
        Schema::table('static_blocks', function (Blueprint $table) {
            $table->string('content', 255)->change(); // Assuming previous type was VARCHAR(255)
        });
    }
};
