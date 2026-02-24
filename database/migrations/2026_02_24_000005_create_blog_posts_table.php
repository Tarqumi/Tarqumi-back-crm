<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar', 200);
            $table->string('title_en', 200);
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->text('excerpt_ar');
            $table->text('excerpt_en');
            $table->longText('content_ar');
            $table->longText('content_en');
            $table->string('featured_image')->nullable();
            $table->string('meta_title_ar', 60)->nullable();
            $table->string('meta_title_en', 60)->nullable();
            $table->string('meta_description_ar', 160)->nullable();
            $table->string('meta_description_en', 160)->nullable();
            $table->string('meta_keywords_ar')->nullable();
            $table->string('meta_keywords_en')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('reading_time')->nullable(); // in minutes
            $table->boolean('is_featured')->default(false);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('slug_ar');
            $table->index('slug_en');
            $table->index('status');
            $table->index('published_at');
            $table->index('category_id');
            $table->index('author_id');
            $table->index('is_featured');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
