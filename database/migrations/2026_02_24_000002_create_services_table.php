<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable(); // Icon name or SVG path
            $table->string('title_ar', 100);
            $table->string('title_en', 100);
            $table->text('description_ar');
            $table->text('description_en');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_home')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('order');
            $table->index('show_on_home');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
