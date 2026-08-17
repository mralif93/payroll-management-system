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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // e.g. "payroll.run", "statutory.edit", "bank.export"
            $table->string('display_name');           // e.g. "Run Monthly Payroll", "Modify Statutory Parameters"
            $table->string('module', 50)->index();    // e.g. "payroll", "statutory", "employees", "banking"
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
