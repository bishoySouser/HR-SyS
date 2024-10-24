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
                $table->enum('follows_instructions', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('accepts_constructive_criticism', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('flexible_&_adaptable', ['improvement_required', 'satisfactory', 'good', 'excellent']);

                // Technical Skills
                $table->enum('job_knowledge', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('follows_procedures', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('works_full_potential', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('learning_new_skills', ['improvement_required', 'satisfactory', 'good', 'excellent']);

                // Quality of work
                $table->enum('accuracy', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('consistency', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('follow_up', ['improvement_required', 'satisfactory', 'good', 'excellent']);

                // Handling target and deadlines
                $table->enum('completion_of_work_on_time', ['improvement_required', 'satisfactory', 'good', 'excellent']);

                // Communication Skills
                $table->enum('share_information/knowledge', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('willingly', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('reporting', ['improvement_required', 'satisfactory', 'good', 'excellent']);

            // BEHAVIOR
                // Interpersonal Skills
                $table->enum('relationship_with_colleagues', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('cooperation', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('coordination', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('team_work', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('punctuality_attendance', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('problem_solving', ['improvement_required', 'satisfactory', 'good', 'excellent']);

                // Willingness to learn
                $table->enum('willingness_to_learn', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('open_to_ideas', ['improvement_required', 'satisfactory', 'good', 'excellent']);
                $table->enum('seeks_training', ['improvement_required', 'satisfactory', 'good', 'excellent']);

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
