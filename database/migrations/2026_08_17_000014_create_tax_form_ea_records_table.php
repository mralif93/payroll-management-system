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
        Schema::create('tax_form_ea_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('tax_year', 4)->index(); // "2026"
            $table->string('serial_no')->unique();   // EA-2026-EMP00104
            $table->string('employer_e_no');         // E 9876543200
            
            // Section B: Employment Income
            $table->decimal('gross_salary_wages', 12, 2)->default(0.00);      // B1(a)
            $table->decimal('fees_commission_bonus', 12, 2)->default(0.00);   // B1(b)
            $table->decimal('gratuity_amount', 12, 2)->default(0.00);         // B1(c)
            $table->decimal('benefits_in_kind', 12, 2)->default(0.00);        // B2 (BIK)
            $table->decimal('value_of_living_accomodation', 12, 2)->default(0.00); // B3 (VOLA)
            $table->decimal('refund_from_unapproved_fund', 12, 2)->default(0.00);  // B4
            $table->decimal('compensation_for_loss_of_employment', 12, 2)->default(0.00); // B5

            // Section C: Pensions & Others
            $table->decimal('pension_annuities', 12, 2)->default(0.00);

            // Section D: Total Deductions
            $table->decimal('total_pcb_mtd', 12, 2)->default(0.00);           // D1 Monthly Tax Deductions
            $table->decimal('total_cp38_deductions', 12, 2)->default(0.00);   // D2 CP38 Court/LHDN orders
            $table->decimal('total_zakat_paid', 12, 2)->default(0.00);        // D3 Zakat via payroll
            $table->decimal('total_tp1_reliefs_claimed', 12, 2)->default(0.00); // TP1 total claimed

            // Section E: Statutory Contributions
            $table->decimal('total_epf_employee', 12, 2)->default(0.00);      // E1 Total KWSP
            $table->decimal('total_socso_employee', 12, 2)->default(0.00);    // Total SOCSO (including SKBBK)
            $table->decimal('total_eis_employee', 12, 2)->default(0.00);      // Total EIS

            // Section F: Tax Exempt Allowances
            $table->decimal('tax_exempt_allowances_total', 12, 2)->default(0.00); // Mileage, petrol, meal exemptions

            $table->string('pdf_path')->nullable();
            $table->boolean('is_published_to_employee')->default(false);
            $table->timestamps();

            $table->unique(['employee_id', 'tax_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_form_ea_records');
    }
};
