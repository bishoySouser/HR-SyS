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
        Schema::table('employee_evaluations', function (Blueprint $table) {
            $table->dropColumn('willingness_to_learn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_evaluations', function (Blueprint $table) {
            $table->enum('willingness_to_learn', ['satisfactory', 'good','very good',  'excellent']);
        });
    }
};
