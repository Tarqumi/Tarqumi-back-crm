<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip_address')->unique();
            $table->enum('reason', ['spam', 'abuse', 'manual'])->default('spam');
            $table->integer('spam_count')->default(0);
            $table->timestamp('blocked_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('blocked_at');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
    }
};
