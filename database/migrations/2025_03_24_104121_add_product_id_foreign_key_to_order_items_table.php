<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdForeignKeyToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the existing product_id column constraints (if any) and re-add it with foreign key
            $table->dropColumn('product_id'); // Remove the old column
            $table->foreignId('product_id')   // Add new column with foreign key constraint
                  ->constrained('products')   // References products(id)
                  ->onDelete('restrict');     // Prevent deletion if referenced
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Reverse the change: drop the constrained column and revert to unsignedBigInteger
            $table->dropForeign(['product_id']); // Drop the foreign key
            $table->dropColumn('product_id');    // Drop the column
            $table->unsignedBigInteger('product_id'); // Revert to original definition
        });
    }
}