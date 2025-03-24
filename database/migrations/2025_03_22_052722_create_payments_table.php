<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();                                   // Primary key: auto-incrementing ID
            $table->foreignId('order_id')                   // Foreign key to orders table
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('amount', 8, 2);                // Payment amount
            $table->string('payment_method');               // Payment method (e.g., 'cod')
            $table->string('status')->default('pending');   // Payment status (e.g., pending, completed)
            $table->timestamps();                           // Created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');                   // Drop the table if it exists
    }
}