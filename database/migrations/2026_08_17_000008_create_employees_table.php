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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('employee_no')->unique(); // EMP-00104
            $table->string('full_name');
            $table->text('nric_passport');           // Encrypted Malaysian NRIC or Passport
            $table->enum('citizenship', ['malaysian', 'permanent_resident', 'foreign_worker'])->default('malaysian');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date');
            $table->date('joined_date');
            $table->date('resigned_date')->nullable();
            $table->enum('employment_status', ['active', 'probation', 'confirmed', 'resigned'])->default('active');
            $table->enum('employment_type', ['permanent', 'contract', 'intern', 'part_time'])->default('permanent');
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->string('designation')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('bank_account_no')->nullable(); // Encrypted Bank Account Number
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
