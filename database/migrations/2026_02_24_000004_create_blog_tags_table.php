<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 50);
            $table->string('name_en', 50);
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->timestamps();
            
            $table->index('slug_ar');
            $table->index('slug_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_tags');
    }
};
