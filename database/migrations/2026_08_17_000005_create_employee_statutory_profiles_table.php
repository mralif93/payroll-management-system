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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_statutory_profiles');
    }
};
