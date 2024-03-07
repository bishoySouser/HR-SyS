<?php

namespace App\Http\Controllers\Admin\Insurance;

use App\Http\Requests\Insurance\MedicalInsuranceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

/**
 * Class medicalInsuranceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MedicalInsuranceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Insurance\MedicalInsurance::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/medical-insurance');
        CRUD::setEntityNameStrings('medical insurance', 'medical insurances');
        // $this->crud->denyAccess('update');
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
        $this->crud->addColumn([
            'name'  => 'status',
            'label' => 'status',
            'type'        => 'enum',
            'options' => [
                0 => 'Disabled',
                1 => 'Active'
            ]
          ]);


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MedicalInsuranceRequest::class);

        CRUD::addField([
            'label'     => "Employee",
            'hint'       => '<b>Only those who were hired 6 months ago</b>',
            'type' => 'select',
            'name' => 'employee_id',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee",
            'options'   => (function ($query) {
                return $query->where('hire_date', '<=', Carbon::now()->subMonths(6))->get();
            }),
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function setupUpdateOperation()
    {
        // CRUD::setValidation(medicalInsuranceRequest::class);
        CRUD::addField([
            'label'     => "Employee",
            'type' => 'select',
            'name' => 'employee_id',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee",
            'attributes' => [
                'disabled'    => 'disabled',
              ],
        ]);

        CRUD::addField(['name' => 'status', 'type' => 'switch']);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }


}
