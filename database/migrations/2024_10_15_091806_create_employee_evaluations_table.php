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
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('employes')->onDelete('cascade');
            $table->integer('year');
            $table->enum('evaluation_type', [
                'quarter_1', 'quarter_2', 'quarter_3', 'quarter_4',
                'end_of_probation', 'end_of_year'
            ]);

            // Performance metrics
                // Approach to work
                $table->enum('follows_instructions', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('accepts_constructive_criticism', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('flexible_and_adaptable', ['satisfactory', 'good', 'very good',  'excellent']);

                // Technical Skills
                $table->enum('job_knowledge', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('follows_procedures', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('works_full_potential', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('learning_new_skills', ['satisfactory', 'good', 'very good',  'excellent']);

                // Quality of work
                $table->enum('accuracy', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('consistency', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('follow_up', ['satisfactory', 'good', 'very good',  'excellent']);

                // Handling target and deadlines
                $table->enum('completion_of_work_on_time', ['satisfactory', 'good', 'very good',  'excellent']);

                // Communication Skills
                $table->enum('share_information/knowledge', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('willingly', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('reporting', ['satisfactory', 'good', 'very good',  'excellent']);

            // BEHAVIOR
                // Interpersonal Skills
                $table->enum('relationship_with_colleagues', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('cooperation', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('coordination', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('team_work', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('punctuality_attendance', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('problem_solving', ['satisfactory', 'good', 'very good',  'excellent']);

                // Willingness to learn
                $table->enum('willingness_to_learn', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('open_to_ideas', ['satisfactory', 'good', 'very good',  'excellent']);
                $table->enum('seeks_training', ['satisfactory', 'good', 'very good',  'excellent']);

            // Text fields
            $table->text('employees_achievements')->nullable();
            $table->text('performance_and_progress');
            $table->text('new_goals_to_achieve');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_evaluations');
    }
};
