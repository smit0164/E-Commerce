<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                                   // Primary key: auto-incrementing ID
            $table->foreignId('customer_id')                // Foreign key to customers table
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('total_amount', 8, 2);          // Total order amount (e.g., 999999.99)
            $table->string('status')->default('pending');   // Order status (e.g., pending, shipped)
            $table->foreignId('shipping_address_id')        // Foreign key to addresses table
                  ->constrained('addresses')
                  ->onDelete('restrict');
            $table->foreignId('billing_address_id')         // Foreign key to addresses table
                  ->constrained('addresses')
                  ->onDelete('restrict');
            $table->timestamps();                           // Created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');                     // Drop the table if it exists
    }
}