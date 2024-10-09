<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HolidayRequest;
use App\Models\Holiday;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HolidayCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HolidayCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Holiday::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/holiday');
        CRUD::setEntityNameStrings('holiday', 'holidays');
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
        CRUD::column('from_date');
        CRUD::column('to_date');
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
        CRUD::setValidation(HolidayRequest::class);

        CRUD::field('name');
        CRUD::field('from_date');
        CRUD::field('to_date');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    // public function store(HolidayRequest $request)
    // {
    //     $event = new Holiday();
    //     $event->name = $request->name;
    //     $event->from_date = $request->from_date;
    //     $event->to_date = $request->to_date;
    //     $event->subject = $request->subject;

    //     $event->save();

    //     $event->employees()->attach($request->employees);

    //     $eventAfterCreated = Event::find($event->id);

    //     $emailsOfEmployees = [];

    //     foreach ($eventAfterCreated->employees as $employee) {
    //         $emailsOfEmployees[] = $employee->email;
    //     }

    //     $email = new MailEvent($event);

    //     Mail::to($emailsOfEmployees)->send($email);


    //     // Check if save_back or save_edit button was submitted
    //     if ($request->has('save_back')) {
    //         return redirect()->url('admin/event')->with('success', 'Event created successfully!');
    //     } else if ($request->has('save_edit')) {
    //         return redirect()->route('admin.event.edit', $event->id)->with('success', 'Event created successfully!');
    //     } else {
    //         // Handle unexpected case (no button submitted)
    //         return redirect()->back()->with('error', 'Unexpected error occurred!');
    //     }
    // }

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
