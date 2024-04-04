<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventRequest;
use App\Models\Employee;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EventCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EventCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Event::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/event');
        CRUD::setEntityNameStrings('event', 'events');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('desc');
        CRUD::column('employees');
        CRUD::column('date');
        CRUD::column('subject');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        CRUD::addColumn([
            'name'      => 'employees', // Name for the custom column
            'label'     => 'Employees',  // Label displayed in the list view
            'type'      => 'closure', // Use a closure to retrieve and format employee data
            'function'  => function ($entity) {
                $employees = $entity->employees;
                $employeeNames = implode(', ', $employees->pluck('full_name')->toArray()); // Use 'full_name' here
                return $employeeNames;
            }
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EventRequest::class);

        CRUD::field('name');
        CRUD::field('desc');
        CRUD::field('date');
        CRUD::field('subject');

        CRUD::addField([
            'label'     => "Employees",
            'type'      => 'select_multiple',
            'name'      => 'employees', // Name of the relationship method in your Event model

            // Optional (if not using the default relationship method name)
            'entity'    => 'employees', // Name of the relationship method in your Event model (optional)

            'model'     => "App\Models\Employee", // Path to your Employee model
            'attribute' => 'full_name', // Attribute to display for employees (e.g., email, full name)
            'pivot'     => true, // Enable pivot table updates on create/update
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
