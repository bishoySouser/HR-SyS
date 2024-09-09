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
        Schema::create('employee_of_the_month', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employes')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->date('month'); // Store the month and year (e.g., 2024-09-01)
            $table->timestamps();

            // Ensure no duplicate entries for the same employee and month
            $table->unique(['employee_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_of_the_month');
    }
};
