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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            // Earnings & Gross Salary
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->decimal('allowances_total', 12, 2)->default(0.00);
            $table->decimal('overtime_total', 12, 2)->default(0.00);
            $table->decimal('bonus_amount', 12, 2)->default(0.00);
            $table->decimal('gross_salary', 12, 2)->default(0.00);
            $table->decimal('unpaid_leave_deduction', 12, 2)->default(0.00);

            // Statutory Subject Wage Bases
            $table->decimal('epf_subject_wages', 12, 2)->default(0.00);
            $table->decimal('socso_subject_wages', 12, 2)->default(0.00);
            $table->decimal('eis_subject_wages', 12, 2)->default(0.00);
            $table->decimal('pcb_subject_wages', 12, 2)->default(0.00);
            $table->decimal('hrd_subject_wages', 12, 2)->default(0.00);

            // Employee Statutory Deductions
            $table->decimal('epf_employee', 12, 2)->default(0.00);
            $table->decimal('socso_employee', 12, 2)->default(0.00);  // Act 4 Base SOCSO
            $table->decimal('skbbk_employee', 12, 2)->default(0.00);  // June 2026 Non-Employment Injury (Lindung 24 Jam)
            $table->decimal('eis_employee', 12, 2)->default(0.00);
            $table->decimal('pcb_amount', 12, 2)->default(0.00);      // LHDN Computerised MTD
            $table->decimal('zakat_amount', 12, 2)->default(0.00);
            $table->decimal('other_deductions_total', 12, 2)->default(0.00);
            $table->decimal('total_employee_deductions', 12, 2)->default(0.00);

            // Employer Statutory Contributions (Company Cost)
            $table->decimal('epf_employer', 12, 2)->default(0.00);
            $table->decimal('socso_employer', 12, 2)->default(0.00);
            $table->decimal('eis_employer', 12, 2)->default(0.00);
            $table->decimal('hrd_levy_employer', 12, 2)->default(0.00);
            $table->decimal('total_employer_contributions', 12, 2)->default(0.00);

            // Net Take-Home & Secured Digital Token
            $table->decimal('net_salary', 12, 2)->default(0.00);
            $table->string('payslip_token', 64)->unique();
            $table->timestamps();

            $table->unique(['payroll_run_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
