<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('national_id');
            $table->date('birthday');
            $table->string('location');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('contract_period');
            $table->date('hire_date')->nullable();
            $table->enum('grades', ['junior', 'associate', 'senior'])->default('junior');
            $table->enum('top_management', ['ceo', 'operation director', 'manager', 'employee'])->default('employee');
            $table->foreignId('job_id')->constrained('jobs');
            $table->decimal('salary', 10, 2)->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('employes');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employes');
    }
}
