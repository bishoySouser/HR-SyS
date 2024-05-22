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
        Schema::dropIfExists('employee_event');
        Schema::create('employee_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('event_id');

            // Define foreign key constraints
            $table->foreign('employee_id')->references('id')->on('employes')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            // Optionally, you can add timestamps
            // $table->timestamps();

            // Define a unique constraint to prevent duplicate combinations of employee_id and event_id
            $table->unique(['employee_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_event');
    }
};
