<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE jobs MODIFY COLUMN grades ENUM('internship', 'junior', 'mid-level', 'executive', 'senior', 'team-lead', 'manager', 'ceo') DEFAULT 'junior'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE jobs MODIFY COLUMN grades ENUM('internship', 'junior', 'executive', 'senior', 'team-lead', 'manager', 'ceo') DEFAULT 'junior'");
    }
};
