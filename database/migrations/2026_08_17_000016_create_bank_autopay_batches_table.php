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
        Schema::create('bank_autopay_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->string('format_type', 50); // maybank2e_fixed, cimb_bizchannel_csv, duitnow_txt
            $table->string('batch_reference_no')->unique(); // MBB-20260828-01
            $table->string('file_path')->nullable();
            $table->integer('total_records')->default(0);
            $table->decimal('total_disbursement_amount', 14, 2)->default(0.00);
            $table->enum('status', ['generated', 'uploaded', 'processed', 'failed'])->default('generated');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_autopay_batches');
    }
};
