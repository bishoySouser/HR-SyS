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
        Schema::create('it_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employes')->onDelete('cascade');

            $table->string('title');
            $table->enum('category', ['computer', 'email', 'network', 'phone', 'other']);
            $table->text('describe');
            $table->text('comment')->nullable();
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->boolean('wait_accountant')->default(false);
            $table->enum('status', ['pending', 'in progress', 'done'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_tickets');
    }
};
