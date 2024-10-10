<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Requests\JobRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class JobCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Job extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\CrudPermissionTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Job::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/job');
        CRUD::setEntityNameStrings('job', 'jobs');
        $this->setAccessUsingPermissions();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');

        CRUD::column('title');
        CRUD::column('max_salary');
        CRUD::column('min_salary');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(JobRequest::class);

        CRUD::addField([
            'name'        => 'grades',
            'label'       => 'Grade',
            'type'        => 'select_from_array',
            'options'     => [
                'internship' => 'internship',
                'junior' => 'junior',
                'mid-level' => 'mid-level',
                'executive' => 'executive',
                'senior' => 'senior',
                'team-lead' => 'team-lead',
                'manager' => 'manager',
                'ceo' => 'ceo'
            ],
            'default'     => 'junior'
        ]);
        CRUD::field('title');
        CRUD::addField([
            'name' => 'min_salary',
            'type' => 'number',
            'default' => 1000,
            'attributes' => [
                "step" => "any",
                'min' => 1000,
            ],
            'prefix' => "L.E",
        ]);

        CRUD::addField([
            'name' => 'max_salary',
            'type' => 'number',
            'attributes' => [
                "step" => "any",
                'min' => 1000,
            ],
            'prefix' => "L.E",
        ]);

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
