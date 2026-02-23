<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('job_title', 100)->nullable();
            $table->string('profile_picture')->nullable();

            // Role management
            $table->enum('role', [
                'super_admin',
                'admin',
                'founder',
                'hr',
                'employee',
            ])->default('employee');

            $table->enum('founder_role', ['ceo', 'cto', 'cfo'])->nullable();

            // Status tracking
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->integer('inactive_days')->default(0);

            // Audit fields
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('role');
            $table->index('is_active');
            $table->index('last_active_at');
            $table->index(['role', 'is_active']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
