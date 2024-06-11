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
        Schema::table('vacations', function (Blueprint $table) {
            // Drop the existing status column
            $table->dropColumn('status');
        });

        Schema::table('vacations', function (Blueprint $table) {
            // Add the new status column with updated enum values
            $table->enum('status', ['pending', 'manager_confirm', 'hr_approved', 'rejected_from_manager', 'rejected_from_hr'])
                  ->default('pending')->after('end_date');

            // Add the reason_rejected column, which is nullable
            $table->string('reason_rejected')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacations', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn('status');
            $table->dropColumn('reason_rejected');

            // Add the old status column back
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }
};
