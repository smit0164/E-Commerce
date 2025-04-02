<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyStatusColumnInStaticPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Add a temporary column to hold the new enum values
        Schema::table('static_pages', function (Blueprint $table) {
            $table->enum('status_temp', ['active', 'inactive'])->nullable()->after('status');
        });

        // Step 2: Migrate existing data from integer to enum
        DB::statement("UPDATE static_pages SET status_temp = CASE status WHEN 1 THEN 'active' WHEN 0 THEN 'inactive' ELSE NULL END");

        // Step 3: Drop the old status column
        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Step 4: Rename the temporary column to status
        Schema::table('static_pages', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });

        // Step 5: Make the status column non-nullable with a default value
        Schema::table('static_pages', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Step 1: Add a temporary integer column
        Schema::table('static_pages', function (Blueprint $table) {
            $table->tinyInteger('status_temp')->nullable()->after('status');
        });

        // Step 2: Migrate enum data back to integer
        DB::statement("UPDATE static_pages SET status_temp = CASE status WHEN 'active' THEN 1 WHEN 'inactive' THEN 0 ELSE NULL END");

        // Step 3: Drop the enum status column
        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Step 4: Rename the temporary column to status
        Schema::table('static_pages', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });

        // Step 5: Make the status column non-nullable with a default value
        Schema::table('static_pages', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->change();
        });
    }
}