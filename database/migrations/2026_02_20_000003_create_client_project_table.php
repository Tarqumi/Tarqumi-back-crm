<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Unique constraint to prevent duplicate client-project pairs
            $table->unique(['client_id', 'project_id']);
            
            // Indexes
            $table->index('client_id');
            $table->index('project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_project');
    }
};
