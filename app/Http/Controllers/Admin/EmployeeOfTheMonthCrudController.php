<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EmployeeOfTheMonthRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EmployeeOfTheMonthCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeOfTheMonthCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\EmployeeOfTheMonth::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee-of-the-month');
        CRUD::setEntityNameStrings('employee of the month', 'employee of the months');
    }
    
    protected function autoSetupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'employee.full_name', // Assuming 'full_name' is the attribute name for the employee full name
            'label' => 'Employee Name',
            'type' => 'text',
        ]);

        CRUD::column('month');   
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::addColumn([
            'name' => 'employee.full_name', // Assuming 'full_name' is the attribute name for the employee full name
            'label' => 'Employee Name',
            'type' => 'text',
        ]);

        CRUD::column('month');
        

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EmployeeOfTheMonthRequest::class);

        CRUD::addField([
            'name' => 'employee_id',
            'type' => 'select', // Assuming you're using Select2 library
            'name'      => 'employee_id',
            'attribute' => 'full_name', // Display the course name in the dropdown
            'model'     => "App\Models\Employee", // Specify the model for fetching courses

            'pivot'     => true,
            // 'options' => (new Course)->all()->pluck('title', 'id'), // Get all courses (name, id)
        ]);

        CRUD::field('month')->type('month')->label('Month');
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
