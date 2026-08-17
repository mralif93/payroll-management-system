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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_no')->unique(); // SSM No (e.g. 202401012345)
            $table->string('epf_no')->nullable();        // Employer EPF No (e.g. 12345678)
            $table->string('socso_no')->nullable();      // Employer SOCSO No (e.g. A123456789)
            $table->string('tax_no')->nullable();        // Employer E No (e.g. E 9876543200)
            $table->string('hrd_no')->nullable();        // HRD Corp Registration
            $table->string('bank_name')->nullable();     // Primary bank (Maybank/CIMB/Public Bank)
            $table->string('bank_account_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('companies');
    }
};
