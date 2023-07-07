<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');
        CRUD::setEntityNameStrings('employee', 'employees');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::column('full_name');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        CRUD::column('manager_id');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }


    public function create(){
        // Widget::add([
        //     'type'     => 'script',
        //     'content'  => 'js/custom/employee.js',
        //     // optional
        //     // 'stack'    => 'before_scripts', // default is after_scripts
        // ])->to('after_content');
        return view("admin.employee.create", $this->data);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EmployeeRequest::class);
        
        CRUD::field('first_name');
        CRUD::field('last_name');
        CRUD::field('email')->type('email');
        CRUD::field('phone_number');
        CRUD::field('hire_date')->type('date');
        CRUD::addField([
            'label'     => "Job",
            'type' => 'select',
            'name' => 'job_id',
            'entity'    => 'jobs',
            'attribute' => 'title',
            'model'     => "App\Models\Job",
        ]); 
        CRUD::addField([
            'name' => 'salary',
            'type' => 'number',
            'attributes' => [
                "step" => "any",
                'min' => 1000,
            ],
            'prefix' => "L.E",
        ]);

        

        CRUD::addField([
            'label'     => "departments",
            'type' => 'select',
            'name' => 'department_id',
            'entity'    => 'department',
            'model'     => "App\Models\Department",
        ]);

        CRUD::addField([
            'name'        => 'role',
            'label'       => 'Role',
            'type'        => 'radio',
            'options'     => [
                0 => 'Manager',
                1 => 'Employee'
            ],
            'default'     => 1
        ],);
        
        CRUD::addField([
            'label'     => "Manager",
            'type' => 'select',
            'name' => 'manager_id',
            'entity'    => 'manager',
            'model'     => "App\Models\Employee",
        ]);

        CRUD::field('username');
        CRUD::field('password')->type('password');


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
        $this->setupCreateOperation();
    }
}
