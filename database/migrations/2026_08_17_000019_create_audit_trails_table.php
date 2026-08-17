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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Classification & Context
            $table->string('module', 50)->index(); // 'payroll', 'auth', 'statutory', 'employees', 'banking', 'system'
            $table->string('event', 100)->index();  // 'user.login', 'payroll.approved', 'statutory.updated', 'employee.salary_adjusted'
            $table->string('description');          // Human-readable summary of the action
            
            // Polymorphic Target Entity
            $table->string('auditable_type')->nullable(); // e.g. "App\Models\PayrollRun", "App\Models\Employee"
            $table->unsignedBigInteger('auditable_id')->nullable();
            
            // State Changes (Old vs New Snapshots)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Client & Network Context
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('severity', 20)->default('info'); // 'info', 'warning', 'critical'
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['module', 'event']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
