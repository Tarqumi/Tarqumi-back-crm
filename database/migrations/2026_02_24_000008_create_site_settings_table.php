<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // site_name, logo, contact_email, phone, etc.
            $table->text('value_ar')->nullable();
            $table->text('value_en')->nullable();
            $table->string('type')->default('text'); // text, image, json, boolean
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
