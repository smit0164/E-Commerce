<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();                                   // Primary key: auto-incrementing ID
            $table->foreignId('order_id')                   // Foreign key to orders table
                  ->constrained()
                  ->onDelete('cascade');
            $table->unsignedBigInteger('product_id');       // Product ID (assumes products table exists)
            $table->integer('quantity');                    // Quantity of the product
            $table->decimal('unit_price', 8, 2);            // Price per unit
            $table->decimal('subtotal', 8, 2);              // Total for this item (unit_price * quantity)
            $table->string('product_name');                 // Product name for reference
            $table->timestamps();                           // Created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');                // Drop the table if it exists
    }
}