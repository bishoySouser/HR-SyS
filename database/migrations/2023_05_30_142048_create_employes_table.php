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
            $table->enum('contract_periods', [
                    'fixed-term contract',
                    'indefinite/termless contract',
                    'renewable contract',
                    'evergreen contract',
                    'month-to-month contract',
                    'project-based contract',
                    'performance-based contract',
                    'trial/probationary period',
                    'fixed-price contract',
                    'milestone-based contract',
                    'lease agreement period',
                    'license agreement period'
                ]
            )->default('renewable contract');
            $table->date('hire_date')->nullable();
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
