<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Requests\DepartmentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DepartmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class Department extends CrudController
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
        CRUD::setModel(\App\Models\Department::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/department');
        CRUD::setEntityNameStrings('department', 'departments');
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
        CRUD::column('name');
        $this->crud->addClause('with', ['manager']);
        $this->crud->addColumn([
            'label' => 'Manager',
            'type' => 'text',
            'name' => 'manager.full_name', // Assuming the column name is 'name' in the Backpack table
        ]);
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
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DepartmentRequest::class);

        CRUD::field('name');
        CRUD::addField([
                'label'     => "Manager",
                'type' => 'select',
                'name' => 'manager_id',
                'entity'    => 'manager',
                'model'     => "App\Models\Employee",
            ]);

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
