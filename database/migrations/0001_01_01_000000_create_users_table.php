<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id', 50)->nullable()->unique(); // e.g. "ADM-001" or "HR-004"
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number', 30)->nullable();
            
            // Access Control & Role Management
            $table->enum('role', [
                'super_admin',      // Full System & Statutory Parameter Control
                'payroll_officer',  // Run batches, export bank & statutory files
                'hr_executive',     // Employee registry & salary components
                'finance_director', // Approve and Lock payroll batches
                'auditor',          // Read-only compliance inspection
                'employee'          // Self-service payslip access
            ])->default('payroll_officer')->index();

            // Account Status & Governance
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Security & Login Activity Tracking
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
