<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItTicket\StoreItTicketRequest;
use App\Http\Requests\ItTicket\UpdateItTicketRequest;
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
    use \App\Traits\CrudPermissionTrait;
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
        $this->setAccessUsingPermissions();
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
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
 * Define what happens when the Show operation is loaded.
 *
 * @see https://backpackforlaravel.com/docs/crud-operation-show
 * @return void
 */
protected function setupShowOperation()
{
    CRUD::set('show.setFromDb', false);

    CRUD::addColumn([
        'name' => 'title',
        'label' => 'Title',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'employee.full_name',
        'label' => 'Employee Name',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'category',
        'label' => 'Category',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'image',
        'label' => 'Image',
        'type' => 'image',
        'prefix' => 'storage/',
        'height' => '100px', // Set the height of the displayed image
        'width' => '100px', // Set the width of the displayed image
        'wrapper' => [ // Wrap the image in an anchor tag
            'element' => 'a',
            'href' => function ($crud, $entry) {
                return asset('storage/'.$entry['value']);
            },
            'target' => '_blank', // Open the image in a new tab
        ],
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
        if (request()->isMethod('post')) {
            CRUD::setValidation(StoreItTicketRequest::class);
        } else {
            CRUD::setValidation(UpdateItTicketRequest::class);
        }

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

        CRUD::addField([   // Upload
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'    => true,
            'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
        ],);

        CRUD::addField([   // Switch
            'name'  => 'wait_accountant',
            'type'  => 'switch',
            'label'    => 'Escalating the issue to the finance department.',

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
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'select_from_array',
            'options'     => [
                'pending' => 'pending',
                'in progress' => 'in progress',
                'done' => 'done'
            ],
            'default'     => 'pending'
        ]);

        $this->setupCreateOperation();

    }
}
