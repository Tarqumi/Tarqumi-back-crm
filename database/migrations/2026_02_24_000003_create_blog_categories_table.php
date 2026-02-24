<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 100);
            $table->string('name_en', 100);
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug_ar');
            $table->index('slug_en');
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
