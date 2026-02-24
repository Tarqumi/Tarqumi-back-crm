<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spam_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('pattern');
            $table->enum('type', ['keyword', 'email', 'url', 'ip'])->default('keyword');
            $table->integer('weight')->default(1); // Spam score weight
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spam_patterns');
    }
};
