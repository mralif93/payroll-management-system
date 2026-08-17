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
    }
};
