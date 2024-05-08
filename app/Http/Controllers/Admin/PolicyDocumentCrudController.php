<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PolicyDocumentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PolicyDocumentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PolicyDocumentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PolicyDocument::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/policy-document');
        CRUD::setEntityNameStrings('policy document', 'policy documents');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->column('title')->type('text');
        CRUD::addColumn([
            'name' => 'file',
            'label' => 'File',
            'type' => 'submit',
            'prefix' => 'Document: ',
            'height' => '100px', // Set the height of the displayed image
            'width' => '100px', // Set the width of the displayed image
            'wrapper' => [ // Wrap the image in an anchor tag
                'element' => 'a',
                'href' => function ($crud, $entry) {
                    return $entry['value'];
                },
                'target' => '_blank', // Open the image in a new tab
            ],
        ]);



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
        CRUD::setValidation(PolicyDocumentRequest::class);


        // Define custom field for image upload
        CRUD::field('title')
            ->type('text')
            ->label('Title');

        // Define custom field for file upload
        CRUD::field('file')
            ->type('upload')
            ->label('PDF File') // Adjust label as needed
            ->disk('local') // Adjust disk name if using a different storage provider
            ->upload(true); // Enable file upload
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
