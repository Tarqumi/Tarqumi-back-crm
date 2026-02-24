<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique(); // home, about, services, projects, blog, contact
            $table->string('title_ar', 60);
            $table->string('title_en', 60);
            $table->string('description_ar', 160);
            $table->string('description_en', 160);
            $table->string('keywords_ar')->nullable();
            $table->string('keywords_en')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
            
            $table->index('page_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
