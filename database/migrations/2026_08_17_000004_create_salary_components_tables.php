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
        // 1. Salary Components Master Definition
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // BASIC, ATT_ALLOW, OT_15, BONUS, UNPAID_LEAVE, COMM
            $table->string('name');               // Basic Salary, Attendance Allowance, Overtime 1.5x, etc.
            $table->enum('type', ['earning', 'deduction', 'allowance', 'reimbursement'])->default('earning');
            
            // Malaysian Statutory Taxability Flags
            $table->boolean('is_epf_subject')->default(true);
            $table->boolean('is_socso_subject')->default(true);
            $table->boolean('is_eis_subject')->default(true);
            $table->boolean('is_pcb_subject')->default(true);
            $table->boolean('is_hrd_subject')->default(true);
            $table->boolean('is_taxable_benefit')->default(false); // Form EA Section B Benefit
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Employee Assigned Salary Components (Recurring or Fixed Additions/Deductions)
        Schema::create('employee_salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('salary_component_id')->constrained('salary_components')->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->date('effective_from');
            $table->date('effective_to')->default('9999-12-31');
            $table->boolean('is_recurring')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'salary_component_id', 'effective_from', 'effective_to'], 'emp_comp_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_components');
        Schema::dropIfExists('salary_components');
    }
};
