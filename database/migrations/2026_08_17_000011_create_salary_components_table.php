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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};
