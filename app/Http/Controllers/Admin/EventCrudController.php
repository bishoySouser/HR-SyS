<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventRequest;
use App\Jobs\EventEmail;
use App\Mail\Event as MailEvent;
use Illuminate\Support\Facades\Notification;
use App\Models\Employee;
use App\Models\Event;
use App\Notifications\EventNotify;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Mail;

/**
 * Class EventCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EventCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Event::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/event');
        CRUD::setEntityNameStrings('event', 'events');
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
        CRUD::column('name');
        CRUD::column('desc');
        CRUD::column('employees');
        CRUD::column('date');
        CRUD::column('subject');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        CRUD::addColumn([
            'name'      => 'employees', // Name for the custom column
            'label'     => 'Employees',  // Label displayed in the list view
            'type'      => 'closure', // Use a closure to retrieve and format employee data
            'function'  => function ($entity) {
                $employees = $entity->employees;
                $employeeNames = implode(', ', $employees->pluck('full_name')->toArray()); // Use 'full_name' here
                return $employeeNames;
            }
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EventRequest::class);

        CRUD::field('name');
        CRUD::field('desc');
        CRUD::field('date');
        CRUD::field('subject');

        CRUD::addField([
            'label'     => "Employees",
            'type'      => 'select_multiple',
            'name'      => 'employees', // Name of the relationship method in your Event model

            // Optional (if not using the default relationship method name)
            'entity'    => 'employees', // Name of the relationship method in your Event model (optional)

            'model'     => "App\Models\Employee", // Path to your Employee model
            'attribute' => 'full_name', // Attribute to display for employees (e.g., email, full name)
            'pivot'     => true, // Enable pivot table updates on create/update
        ]);




    }

    // EventController.php (or wherever you handle event creation)
public function store(EventRequest $request)
{
    $event = new Event();
    $event->name = $request->name;
    $event->desc = $request->desc;
    $event->date = $request->date;
    $event->subject = $request->subject;

    $event->save();

    $event->employees()->attach($request->employees);

    $eventAfterCreated = Event::find($event->id);

    $emailsOfEmployees = [];

    foreach ($eventAfterCreated->employees as $employee) {
        $emailsOfEmployees[] = $employee->email;
    }

    $email = new MailEvent($event);
    Mail::to($emailsOfEmployees)->send($email);


    // Check if save_back or save_edit button was submitted
    if ($request->has('save_back')) {
        return redirect()->url('admin/event')->with('success', 'Event created successfully!');
    } else if ($request->has('save_edit')) {
        return redirect()->route('admin.event.edit', $event->id)->with('success', 'Event created successfully!');
    } else {
        // Handle unexpected case (no button submitted)
        return redirect()->back()->with('error', 'Unexpected error occurred!');
    }
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
