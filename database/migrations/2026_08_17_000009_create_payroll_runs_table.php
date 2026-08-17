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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
