<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->enum('grades', ['internship' ,'junior', 'executive', 'senior', 'team-lead', 'manager', 'ceo'])->default('junior');
            $table->string('title');
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
