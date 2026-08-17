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
        // 1. Monthly Payroll Runs (Batches)
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('batch_no')->unique(); // RUN-2026-08-01
            $table->string('period_year', 4);    // 2026
            $table->string('period_month', 2);   // 08
            $table->date('cutoff_date');         // 2026-08-25
            $table->date('payment_date');        // 2026-08-28
            $table->enum('status', ['draft', 'reviewed', 'approved', 'paid', 'locked'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('approved_at')->nullable();
            
            // High-Level Batch Summaries
            $table->integer('total_headcount')->default(0);
            $table->decimal('total_gross_amount', 14, 2)->default(0.00);
            $table->decimal('total_statutory_employee', 14, 2)->default(0.00);
            $table->decimal('total_statutory_employer', 14, 2)->default(0.00);
            $table->decimal('total_net_disbursement', 14, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'period_year', 'period_month']);
        });

        // 2. Payroll Items (Per-Employee Monthly Payslip Calculations)
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

        // 3. Payroll Item Breakdowns (Detailed line items for Payslip & Form EA)
        Schema::create('payroll_item_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->foreignId('salary_component_id')->nullable()->constrained('salary_components')->nullOnDelete();
            $table->string('name'); // e.g. "Overtime 1.5x (4 hours)", "Mobile Allowance"
            $table->enum('type', ['earning', 'deduction', 'allowance', 'reimbursement'])->default('earning');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_item_breakdowns');
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
    }
};
