<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WorkFromHomeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WorkFromHomeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WorkFromHomeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\WorkFromHome::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/work-from-home');
        CRUD::setEntityNameStrings('work from home', 'work from homes');
        $this->setAccessUsingPermissions();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
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
        CRUD::column('day');
        CRUD::column('status');

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(WorkFromHomeRequest::class);
        CRUD::addField([
            'label'     => "Employee",
            'type' => 'select',
            'name' => 'employee_id',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee"
        ]);
        CRUD::field('day');
        CRUD::field('employee_note');
        // CRUD::field('status')->type('enum')->options(['Pending', 'Accepted by manager', 'Approved', 'Cancelled']);
        CRUD::addField([
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'select_from_array',
            'options'     => [
                'Pending' => 'Pending',
                'Accepted by manager' => 'Accepted by manager',
                'Approved' => 'Approved',
                'Cancelled' => 'Cancelled',
            ],
            'default'     => 'Pending'
        ],);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        // $this->setupCreateOperation();
        CRUD::addField([
            'label'     => "Employee",
            'type' => 'select',
            'name' => 'employee.fname',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee",
            'options'   => (function ($query) {
                return $query->orderBy('full_name', 'ASC')->get();
            }),
            'attributes' => [
                'disabled'    => 'disabled',
                'readonly'    => 'readonly',
              ],
        ]);

        CRUD::field('day');
        CRUD::field('employee_note');
        // CRUD::field('status')->type('enum')->options(['Pending', 'Accepted by manager', 'Approved', 'Cancelled']);
        CRUD::addField([
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'select_from_array',
            'options'     => [
                'Pending' => 'Pending',
                'Accepted by manager' => 'Accepted by manager',
                'Approved' => 'Approved',
                'Cancelled' => 'Cancelled',
            ],
            'default'     => 'Pending'
        ],);
    }
}
