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
        // 1. Employees Core Registry
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('employee_no')->unique(); // EMP-00104
            $table->string('full_name');
            $table->text('nric_passport');           // Encrypted Malaysian NRIC (e.g. 880415-14-5531) or Passport
            $table->enum('citizenship', ['malaysian', 'permanent_resident', 'foreign_worker'])->default('malaysian');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date');
            $table->date('joined_date');
            $table->date('resigned_date')->nullable();
            $table->enum('employment_status', ['active', 'probation', 'confirmed', 'resigned'])->default('active');
            $table->enum('employment_type', ['permanent', 'contract', 'intern', 'part_time'])->default('permanent');
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->string('designation')->nullable();
            $table->string('bank_name')->nullable();       // Maybank, CIMB, Public Bank, etc.
            $table->text('bank_account_no')->nullable();   // Encrypted Bank Account Number
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });

        // 2. Employee Statutory & Tax Profiles
        Schema::create('employee_statutory_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            
            // KWSP / EPF
            $table->string('epf_member_no')->nullable();
            $table->enum('epf_rate_type', ['standard_11', 'reduced_9', 'custom'])->default('standard_11');
            $table->decimal('epf_employee_custom_rate', 5, 2)->nullable();
            $table->decimal('epf_employer_custom_rate', 5, 2)->nullable();
            
            // PERKESO / SOCSO & SKBBK
            $table->string('socso_member_no')->nullable();
            $table->enum('socso_category', ['category_1_full', 'category_2_injury_only'])->default('category_1_full');
            $table->boolean('is_eis_contributed')->default(true);
            $table->boolean('is_skbbk_contributed')->default(true); // June 2026 Non-employment Injury Scheme
            
            // LHDN Income Tax & PCB MTD
            $table->string('income_tax_no')->nullable(); // LHDN Tax File No (SG / OG)
            $table->enum('tax_category', ['single', 'married_non_working', 'married_working'])->default('single');
            $table->boolean('is_tax_resident')->default(true);
            $table->integer('number_of_children')->default(0);
            $table->boolean('is_disabled')->default(false);
            $table->boolean('spouse_is_disabled')->default(false);
            $table->decimal('monthly_zakat_amount', 12, 2)->default(0.00);
            $table->decimal('total_tp1_relief_amount', 12, 2)->default(0.00); // LHDN TP1 Form Declared Reliefs
            $table->timestamps();
        });

        // 3. Employee Dependents (For LHDN Form EA & Tax Child Relief Validation)
        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('full_name');
            $table->enum('relationship', ['spouse', 'child', 'disabled_child'])->default('child');
            $table->date('birth_date')->nullable();
            $table->boolean('is_studying_higher_education')->default(false); // RM8,000 relief rule
            $table->boolean('is_disabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_dependents');
        Schema::dropIfExists('employee_statutory_profiles');
        Schema::dropIfExists('employees');
    }
};
