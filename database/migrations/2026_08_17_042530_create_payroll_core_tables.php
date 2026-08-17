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
        // 1. Companies / Employers Registry
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_no')->unique(); // SSM No
            $table->string('epf_no')->nullable();        // Employer EPF No
            $table->string('socso_no')->nullable();      // Employer SOCSO No
            $table->string('tax_no')->nullable();        // Employer E No (LHDN)
            $table->string('hrd_no')->nullable();        // HRD Corp Registration
            $table->string('bank_name')->nullable();     // Maybank / CIMB
            $table->string('bank_account_no')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 2. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });

        // 3. Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_no')->unique(); // EMP-00104
            $table->string('full_name');
            $table->text('nric_passport'); // Encrypted PII
            $table->enum('citizenship', ['malaysian', 'permanent_resident', 'foreign_worker'])->default('malaysian');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date');
            $table->date('joined_date');
            $table->date('resigned_date')->nullable();
            $table->enum('employment_status', ['active', 'probation', 'confirmed', 'resigned'])->default('active');
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->string('designation')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('bank_account_no')->nullable(); // Encrypted PII
            $table->timestamps();
        });

        // 4. Employee Statutory Profile
        Schema::create('employee_statutory_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('epf_member_no')->nullable();
            $table->enum('epf_rate_type', ['standard_11', 'reduced_9', 'custom'])->default('standard_11');
            $table->decimal('epf_employee_custom_rate', 5, 2)->nullable();
            $table->decimal('epf_employer_custom_rate', 5, 2)->nullable();
            $table->string('socso_member_no')->nullable();
            $table->enum('socso_category', ['category_1_full', 'category_2_injury_only'])->default('category_1_full');
            $table->boolean('is_eis_contributed')->default(true);
            $table->boolean('is_skbbk_contributed')->default(true); // June 2026 Non-employment 24-hr injury scheme
            $table->string('income_tax_no')->nullable();
            $table->enum('tax_category', ['single', 'married_non_working', 'married_working'])->default('single');
            $table->boolean('is_tax_resident')->default(true);
            $table->integer('number_of_children')->default(0);
            $table->boolean('is_disabled')->default(false);
            $table->boolean('spouse_is_disabled')->default(false);
            $table->decimal('monthly_zakat_amount', 12, 2)->default(0.00);
            $table->decimal('total_tp1_relief_amount', 12, 2)->default(0.00);
            $table->timestamps();
        });

        // 5. Salary Components Catalog
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // BASIC, ATT_ALLOW, OT_15, BONUS, UNPAID_LEAVE
            $table->string('name');
            $table->enum('type', ['earning', 'deduction', 'allowance', 'reimbursement'])->default('earning');
            $table->boolean('is_epf_subject')->default(true);
            $table->boolean('is_socso_subject')->default(true);
            $table->boolean('is_eis_subject')->default(true);
            $table->boolean('is_pcb_subject')->default(true);
            $table->boolean('is_hrd_subject')->default(true);
            $table->boolean('is_taxable_benefit')->default(false); // For Form EA
            $table->timestamps();
        });

        // 6. Payroll Runs
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('batch_no')->unique(); // RUN-2026-08-01
            $table->string('period_year', 4);    // 2026
            $table->string('period_month', 2);   // 08
            $table->date('cutoff_date');
            $table->date('payment_date');
            $table->enum('status', ['draft', 'reviewed', 'approved', 'paid', 'locked'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('approved_at')->nullable();
            $table->decimal('total_gross_amount', 14, 2)->default(0.00);
            $table->decimal('total_statutory_employee', 14, 2)->default(0.00);
            $table->decimal('total_statutory_employer', 14, 2)->default(0.00);
            $table->decimal('total_net_disbursement', 14, 2)->default(0.00);
            $table->timestamps();
        });

        // 7. Payroll Items (Per Employee Payslip Computations)
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            // Earnings & Base
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->decimal('allowances_total', 12, 2)->default(0.00);
            $table->decimal('overtime_total', 12, 2)->default(0.00);
            $table->decimal('gross_salary', 12, 2)->default(0.00);
            $table->decimal('unpaid_leave_deduction', 12, 2)->default(0.00);

            // Subject Wage Bases
            $table->decimal('epf_subject_wages', 12, 2)->default(0.00);
            $table->decimal('socso_subject_wages', 12, 2)->default(0.00);
            $table->decimal('pcb_subject_wages', 12, 2)->default(0.00);

            // Employee Deductions
            $table->decimal('epf_employee', 12, 2)->default(0.00);
            $table->decimal('socso_employee', 12, 2)->default(0.00);
            $table->decimal('skbbk_employee', 12, 2)->default(0.00); // 2026 Scheme
            $table->decimal('eis_employee', 12, 2)->default(0.00);
            $table->decimal('pcb_amount', 12, 2)->default(0.00);
            $table->decimal('zakat_amount', 12, 2)->default(0.00);
            $table->decimal('other_deductions_total', 12, 2)->default(0.00);
            $table->decimal('total_employee_deductions', 12, 2)->default(0.00);

            // Employer Contributions
            $table->decimal('epf_employer', 12, 2)->default(0.00);
            $table->decimal('socso_employer', 12, 2)->default(0.00);
            $table->decimal('eis_employer', 12, 2)->default(0.00);
            $table->decimal('hrd_levy_employer', 12, 2)->default(0.00);
            $table->decimal('total_employer_contributions', 12, 2)->default(0.00);

            // Final Net Pay & Token
            $table->decimal('net_salary', 12, 2)->default(0.00);
            $table->string('payslip_token', 64)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('salary_components');
        Schema::dropIfExists('employee_statutory_profiles');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('companies');
    }
};
