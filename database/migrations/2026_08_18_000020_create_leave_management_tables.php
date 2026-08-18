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
        // 1. Leave Types (Annual, Sick, Hospitalization, Maternity, Paternity, Unpaid)
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // e.g. Annual Leave, Medical Leave
            $table->string('code')->unique();            // AL, MC, HL, ML, PL, UL
            $table->boolean('is_paid')->default(true);   // false = Unpaid / No-Pay Leave
            $table->integer('default_days_per_year')->default(14);
            $table->string('color')->default('indigo');  // Badge color indicator
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Employee Leave Quotas / Balances per Year
        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->year('year');
            $table->decimal('total_entitled', 5, 1)->default(14.0);
            $table->decimal('taken_days', 5, 1)->default(0.0);
            $table->decimal('pending_days', 5, 1)->default(0.0);
            $table->decimal('remaining_days', 5, 1)->default(14.0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type_id', 'year']);
        });

        // 3. Leave Applications & Records
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 4, 1)->default(1.0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('approved');
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('employee_leave_balances');
        Schema::dropIfExists('leave_types');
    }
};
