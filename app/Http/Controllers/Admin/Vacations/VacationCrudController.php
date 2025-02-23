<?php

namespace App\Http\Controllers\Admin\Vacations;

use App\Http\Requests\VacationRequest;
use App\Models\Employee;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class VacationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VacationCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Vacation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vacation');
        CRUD::setEntityNameStrings('vacation', 'vacations');
        $this->setAccessUsingPermissions();
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumn([
            'name' => 'employee.full_name', // Name of the column
            'label' => 'Employee Name', // Label for the column header
            'type' => 'closure', // Use a closure to retrieve the data
            'function' => function ($entry) {
                return $entry->balance->employee->full_name; // Access the full_name through relationships
            },
        ]);
        CRUD::column('balance_id');
        CRUD::column('start_date');
        CRUD::column('end_date');
        CRUD::column('duration');
        CRUD::column('status');
        CRUD::column('created_at');
        CRUD::column('updated_at');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name' => 'employee.full_name',
            'label' => 'Employee Name',
            'type' => 'relationship',
            'entity' => 'employee',
            'attribute' => 'full_name',
            'model' => 'App\Models\Employee',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('employee', function ($q) use ($searchTerm) {
                    $q->where('full_name', 'LIKE', '%'.$searchTerm.'%');
                });
            }
        ]);
        CRUD::column('balance_id');
        CRUD::column('start_date');
        CRUD::column('end_date');
        CRUD::column('duration');
        CRUD::column('status');
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
        CRUD::setValidation(VacationRequest::class);

        CRUD::addField([
            'label'     => "Balance",
            'type' => 'select',
            'name' => 'balance_id',
            'entity'    => 'balance',
            'model'     => "App\Models\VacationBalance",
            'attribute' => 'name',
            'options'   => function ($query) {
                $currentYear = now()->year;

                return $query->join("employes", "employes.id", "=", "leave_balance.employee_id")
                                ->where('leave_balance.year', $currentYear)
                                ->select("leave_balance.id","employes.full_name as name")->get();
            },
        ]);

        CRUD::addField([
            'name' => 'start_date',
            'label' => 'Start Date',
            'type' => 'date',
        ]);

        CRUD::addField([
            'name' => 'end_date',
            'label' => 'End Date',
            'type' => 'date',
        ]);

        CRUD::addField([
            'name' => 'duration',
            'label' => 'Duration',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Pending',
                'manager_confirm' => 'Manager confirm',
                'hr_approved' => 'HR Approved',
                'rejected_from_manager' => 'Rejected from manager',
                'rejected_from_hr' => 'Rejected from hr'

            ],
            'default' => 'pending'
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
