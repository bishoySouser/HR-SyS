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
        Schema::create('best_employee_in_team', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('manager_id')->constrained('employees')->onDelete('cascade'); // FK to employees (manager)
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // FK to employees (best employee)
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); // FK to departments
            $table->date('vote_date'); // Date of the vote
            $table->timestamps(); // Created and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_employee_in_team');
    }
};
