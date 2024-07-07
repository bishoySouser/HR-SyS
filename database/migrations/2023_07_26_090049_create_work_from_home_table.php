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
        Schema::create('work_from_home', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employes');
            $table->date('day');
            $table->text('employee_note')->nullable();
            $table->enum('status', ['Pending', 'Accepted by manager', 'Approved', 'Cancelled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_from_home');
    }
};
