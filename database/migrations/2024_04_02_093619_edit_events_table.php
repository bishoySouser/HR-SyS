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
        // Remove the old 'subject' column
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('subject');
        });

        // Add the new 'subject' column
        Schema::table('events', function (Blueprint $table) {
            $table->string('subject')->after('date');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop the new STRING column
            $table->dropColumn('subject');
            // Recreate the ENUM column
            // $table->enum('subject', ['training', 'webinars', 'conferences', 'exhibitions', 'seminars', 'hackathons', 'professional development programs'])->after('date');
        });
    }
};
