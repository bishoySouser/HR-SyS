<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TrashedEmployeeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \App\Traits\CrudPermissionTrait;

    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/trashed-employee');
        CRUD::setEntityNameStrings('trashed employee', 'trashed employees');
        $this->setAccessUsingPermissions();
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('full_name');
        CRUD::column('profile_pic')->type('image');
        CRUD::column('hire_date');
        CRUD::column('manager_id');
        CRUD::column('department_id');
        CRUD::column('deleted_at');

        CRUD::addButton('line', 'restore', 'view', 'crud::buttons.restore', 'end');

        // Only show trashed items
        $this->crud->addClause('onlyTrashed');
    }

    public function restore($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $employee = $this->crud->model::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return response()->json(['status' => 'success', 'message' => 'The employee has been restored.']);
    }
}
