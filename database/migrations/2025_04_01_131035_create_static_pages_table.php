<?php

// database/migrations/2023_10_02_xxxxxx_create_static_pages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticPagesTable extends Migration
{
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');                    // Page title (e.g., "About Us")
            $table->string('slug')->unique();           // Unique URL slug (e.g., "about-us")
            $table->longText('content');                // Rich text content (for Summernote)
            $table->string('meta_title')->nullable();   // SEO title (e.g., "About Us | Your Store")
            $table->text('meta_description')->nullable(); // SEO meta description
            $table->boolean('status')->default(1);      // Changed from 'is_active' to 'status' (1 = active, 0 = inactive)
            $table->timestamps();                       // created_at, updated_at
            $table->softDeletes();                      // deleted_at for soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('static_pages');
    }
}
