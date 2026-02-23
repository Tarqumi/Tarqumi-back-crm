<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('manager_id')->constrained('users')->restrictOnDelete();
            $table->decimal('budget', 15, 2)->default(0);
            $table->string('currency', 3)->default('SAR');
            $table->tinyInteger('priority')->default(5);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->string('status')->default('planning');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index('name');
            $table->index('manager_id');
            $table->index('status');
            $table->index('is_active');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'is_active']);
            $table->index(['priority', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
