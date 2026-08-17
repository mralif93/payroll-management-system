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
        Schema::create('statutory_parameters', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['epf', 'socso', 'skbbk', 'eis', 'pcb', 'hrd'])->index();
            $table->string('parameter_key', 100)->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->json('value_payload');
            $table->date('effective_from')->index();
            $table->date('effective_to')->default('9999-12-31')->index();
            $table->boolean('is_active')->default(true);
            $table->string('reference_gazette')->nullable();
            $table->timestamps();

            $table->index(['category', 'parameter_key', 'effective_from', 'effective_to'], 'statutory_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statutory_parameters');
    }
};
