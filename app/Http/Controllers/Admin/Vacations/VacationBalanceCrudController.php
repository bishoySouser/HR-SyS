<?php

namespace App\Http\Controllers\Admin\Vacations;

use App\Http\Requests\VacationBalanceRequest;
use App\Models\VacationBalance;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Alert;


/**
 * Class VacationBalanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VacationBalanceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\CrudPermissionTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\VacationBalance::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vacation-balance');
        CRUD::setEntityNameStrings('vacation balance', 'vacation balance');
        $this->setAccessUsingPermissions();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupShowOperation()
    {


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        CRUD::column('year')->type('text');
        CRUD::column('employee_id');
        CRUD::column('remaining_days');



    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        CRUD::column('year')->type('text');
        CRUD::column('employee_id');
        CRUD::column('remaining_days');



    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(VacationBalanceRequest::class);

        CRUD::addField([
            'name' => 'year',
            'type' => 'number',
            'label' => 'year',
            'value' => Date('Y'),
            'attributes' => [
                'readonly' => 'readonly'
              ],
          ]);

        CRUD::addField([
            'label'     => "Employee",
            'type' => 'select',
            'name' => 'employee_id',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee",
        ]);

        // CRUD::field('remaining_days')->type('number');
        CRUD::addField([
            'name' => 'remaining_days',
            'type' => 'number',
            'label' => 'Remaining days',
            'default' => '21',
            'min' => '1',
        ]);

        CRUD::addField([
            'name' => 'expiry_date',
            'type' => 'date',
            'label' => 'Expiry date'
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

    public function destroy($id)
    {

        $this->crud->hasAccessOrFail('delete');

        try {
            $response = $this->crud->delete($id);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Delete failed',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
