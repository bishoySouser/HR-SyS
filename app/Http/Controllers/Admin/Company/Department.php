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
        CRUD::column('id');
        CRUD::column('name');

        // CRUD::column('name')->remove();
        $this->crud->addClause('with', ['manager']);
        $this->crud->addColumn([
            'label' => 'Manager',
            'type' => 'text',
            'name' => 'manager.full_name', // Assuming the column name is 'name' in the Backpack table
        ]);
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
        CRUD::setValidation(DepartmentRequest::class);

        CRUD::field('name');

        CRUD::removeSaveActions(['save_and_back','save_and_preview']);

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
        CRUD::addField([
                'label'     => "Manager",
                'type' => 'select',
                'name' => 'manager_id',
                'entity'    => 'manager',
                'model'     => "App\Models\Employee",
        ]);

        CRUD::orderSaveAction('save_and_new', 1);

        CRUD::addSaveAction([
            'name' => 'save_action_one',
            'redirect' => function($crud, $request, $itemId) {
                return $crud->route;
            }, // what's the redirect URL, where the user will be taken after saving?

            // OPTIONAL:
            'button_text' => 'Save and back', // override text appearing on the button
            // You can also provide translatable texts, for example:
            // 'button_text' => trans('backpack::crud.save_action_one'),
            'visible' => function($crud) {
                return true;
            }, // customize when this save action is visible for the current operation
            'referrer_url' => function($crud, $request, $itemId) {
                return $crud->route;
            }, // override http_referrer_url
            'order' => 1, // change the order save actions are in
        ]);


    }
}
