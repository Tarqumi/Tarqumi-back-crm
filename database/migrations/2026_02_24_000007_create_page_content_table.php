<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_content', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug'); // home, about, services, projects, contact
            $table->string('section_key'); // hero_title, hero_subtitle, about_mission, etc.
            $table->text('value_ar')->nullable();
            $table->text('value_en')->nullable();
            $table->string('image')->nullable();
            $table->json('metadata')->nullable(); // For additional data like button links, etc.
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->unique(['page_slug', 'section_key']);
            $table->index('page_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_content');
    }
};
