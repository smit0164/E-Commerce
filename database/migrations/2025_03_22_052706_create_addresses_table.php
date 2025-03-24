<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();                                   // Primary key: auto-incrementing ID
            $table->foreignId('customer_id')                // Foreign key to customers table
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('type');                         // 'shipping' or 'billing'
            $table->string('full_name');                    // Full name for the address
            $table->string('phone', 10);                    // Phone number, 10 digits
            $table->string('address_line1');                // Main address line
            $table->string('address_line2')->nullable();    // Optional second address line
            $table->string('city');                         // City name
            $table->string('state');                        // State name
            $table->string('postal_code');                  // Postal/ZIP code
            $table->string('country');                      // Country name
            $table->boolean('is_default')->default(true);   // Marks default address
            $table->timestamps();                           // Created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');                  // Drop the table if it exists
    }
}