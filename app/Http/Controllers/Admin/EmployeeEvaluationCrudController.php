<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeEvaluationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeEvaluationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeEvaluationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EmployeeEvaluation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee-evaluation');
        CRUD::setEntityNameStrings('employee evaluation', 'employee evaluations');
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        // Add all the columns that should appear in the show view
        CRUD::set('show.setFromDb', false);

        // Add the columns manually - more control
        CRUD::addColumn([
            'name' => 'employees_achievements',
            'label' => "Employee's Achievements",
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'name' => 'performance_and_progress',
            'label' => 'Performance and Progress',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'name' => 'new_goals_to_achieve',
            'label' => 'New Goals',
            'type' => 'text'
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('employee_id');
        CRUD::column('evaluator_id');
        CRUD::column('year')->type('text');
        CRUD::column('evaluation_type');
        // CRUD::column('follows_instructions');
        // CRUD::column('accepts_constructive_criticism');
        // CRUD::column('flexible_&_adaptable');
        // CRUD::column('job_knowledge');
        // CRUD::column('follows_procedures');
        // CRUD::column('works_full_potential');
        // CRUD::column('learning_new_skills');
        // CRUD::column('accuracy');
        // CRUD::column('consistency');
        // CRUD::column('follow_up');
        // CRUD::column('completion_of_work_on_time');
        // CRUD::column('share_information/knowledge');
        // CRUD::column('willingly');
        // CRUD::column('reporting');
        // CRUD::column('relationship_with_colleagues');
        // CRUD::column('cooperation');
        // CRUD::column('coordination');
        // CRUD::column('team_work');
        // CRUD::column('punctuality_attendance');
        // CRUD::column('problem_solving');
        // CRUD::column('open_to_ideas');
        // CRUD::column('seeks_training');
        // CRUD::column('employees_achievements');
        // CRUD::column('performance_and_progress');
        // CRUD::column('new_goals_to_achieve');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    // protected function setupCreateOperation()
    // {
    //     CRUD::setValidation(EmployeeEvaluationRequest::class);

    //     CRUD::field('employee_id');
    //     CRUD::field('evaluator_id');
    //     CRUD::field('year');
    //     CRUD::field('evaluation_type');
    //     CRUD::field('follows_instructions');
    //     CRUD::field('accepts_constructive_criticism');
    //     CRUD::field('flexible_&_adaptable');
    //     CRUD::field('job_knowledge');
    //     CRUD::field('follows_procedures');
    //     CRUD::field('works_full_potential');
    //     CRUD::field('learning_new_skills');
    //     CRUD::field('accuracy');
    //     CRUD::field('consistency');
    //     CRUD::field('follow_up');
    //     CRUD::field('completion_of_work_on_time');
    //     CRUD::field('share_information/knowledge');
    //     CRUD::field('willingly');
    //     CRUD::field('reporting');
    //     CRUD::field('relationship_with_colleagues');
    //     CRUD::field('cooperation');
    //     CRUD::field('coordination');
    //     CRUD::field('team_work');
    //     CRUD::field('punctuality_attendance');
    //     CRUD::field('problem_solving');
    //     CRUD::field('open_to_ideas');
    //     CRUD::field('seeks_training');
    //     CRUD::field('employees_achievements');
    //     CRUD::field('performance_and_progress');
    //     CRUD::field('new_goals_to_achieve');

    //     /**
    //      * Fields can be defined using the fluent syntax or array syntax:
    //      * - CRUD::field('price')->type('number');
    //      * - CRUD::addField(['name' => 'price', 'type' => 'number']));
    //      */
    // }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    // protected function setupUpdateOperation()
    // {
    //     $this->setupCreateOperation();
    // }
}
