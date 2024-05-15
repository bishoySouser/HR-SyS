<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Course;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class EnrollmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EnrollmentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Enrollment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/enrollment');
        CRUD::setEntityNameStrings('enrollment', 'enrollments');
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
            'name' => 'course.title', // Assuming 'title' is the attribute name for the course title
            'label' => 'Course Title',
            'type' => 'text',
        ]);

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
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EnrollmentRequest::class);

        CRUD::field('course_id');
        CRUD::field('employee_id');
        CRUD::field('link');
        CRUD::field('note');

        CRUD::addField([
            'name' => 'course_id',
            'type' => 'select', // Assuming you're using Select2 library
            'name'      => 'course_id',
            'attribute' => 'title', // Display the course name in the dropdown
            'model'     => "App\Models\Course", // Specify the model for fetching courses

            'pivot'     => true,
            // 'options' => (new Course)->all()->pluck('title', 'id'), // Get all courses (name, id)
        ]);

        CRUD::addField([
            'name' => 'employee_id',
            'type' => 'select', // Assuming you're using Select2 library
            'name'      => 'employee_id',
            'attribute' => 'full_name', // Display the course name in the dropdown
            'model'     => "App\Models\Employee", // Specify the model for fetching courses

            'pivot'     => true,
            // 'options' => (new Course)->all()->pluck('title', 'id'), // Get all courses (name, id)
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
