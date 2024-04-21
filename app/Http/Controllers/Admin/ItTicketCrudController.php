<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItTicketRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ItTicketCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ItTicketCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ItTicket::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/it-ticket');
        CRUD::setEntityNameStrings('it ticket', 'it tickets');
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
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ItTicketRequest::class);

        CRUD::field('title');
        CRUD::addField([
            'label'     => "Employee",
            'type' => 'select',
            'name' => 'employee_id',
            'entity'    => 'employee',
            'model'     => "App\Models\Employee",
        ]);

        CRUD::addField([
            'name'        => 'category',
            'label'       => 'Issue for',
            'type'        => 'select_from_array',
            'options'     => [
                'computer' => 'computer',
                'email' => 'email',
                'network' => 'network',
                'phone' => 'phone',
                'other' => 'other'
            ],
            'default'     => 'computer'
        ]);

        CRUD::field('describe');
        CRUD::field('comment');
        CRUD::field('note');

        CRUD::addField([
            'name'          => 'image',
            'label'         => 'Attach Image (Optional)',
            'type'          => 'upload',
            'upload_true_label' => 'Upload',
            'upload_false_label' => 'Remove', // Customize upload button labels
            'nullable'       => true, // Allow creating tickets without an image
            'disk'           => 'public', // Specify disk for image storage (if applicable)
        ]);

        CRUD::addField([   // Switch
            'name'  => 'wait_accountant',
            'type'  => 'switch',
            'label'    => 'Converting the issue to the finance department.',

            // optional
            'color'    => 'primary', // May be any bootstrap color class or an hex color
            'onLabel' => '✓',
            'offLabel' => '✕',
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
        CRUD::addField([
            'name'        => 'category',
            'label'       => 'Issue for',
            'type'        => 'select_from_array',
            'options'     => [
                'computer' => 'computer',
                'email' => 'email',
                'network' => 'network',
                'phone' => 'phone',
                'other' => 'other'
            ],
            'default'     => 'computer'
        ]);
    }
}
