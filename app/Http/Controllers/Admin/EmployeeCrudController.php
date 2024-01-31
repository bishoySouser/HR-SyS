<?php

namespace App\Http\Controllers\Admin;

use App\Abstracts\RedirectManager;
use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Repositories\Employee\EmployeeInterface;
use Backpack\CRUD\app\Library\Widget;
use App\Models\Job;
use Illuminate\Http\Request;

/**
 * Class EmployeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EmployeeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    protected $employeeInterface;

    public function __construct(EmployeeInterface $employeeInterface)
    {
        parent::__construct();
        $this->employeeInterface = $employeeInterface;
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Employee::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/employee');
        CRUD::setEntityNameStrings('employee', 'employees');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::column('id');
        CRUD::column('full_name');
        CRUD::column('hire_date');
        CRUD::column('manager_id');
        CRUD::column('department_id');
        CRUD::column('updated_at');
        CRUD::column('created_at');
        CRUD::setOperationSetting('lineButtonsAsDropdown', true);

        $this->crud->addButtonFromModelFunction('line', 'open_google', 'openVacations', 'end');
    }

    /**
     * Define what happens when the create operation is loaded.
     *
     * @return void
     */
    // public function create(){
    //     $jobs = Job::select('id', 'title', 'min_salary', 'max_salary')->get();

    //     $departments = Department::select('id', 'name', 'manager_id')->with('manager')->get();

    //     $employes = Employee::select('id', 'full_name')->get();

    //     $data = [
    //         'jobs' => $jobs,
    //         'departments' => $departments,
    //         'employes' => $employes,
    //     ];

    //     return view("admin.employee.create", compact('data') );
    // }

    /**
     * Define what happens when the edit operation is loaded.
     *
     * @return void
     */
    public function edit($id){
        $employee = Employee::findOrFail($id);

        $jobs = Job::select('id', 'title', 'min_salary', 'max_salary')->get();

        $departments = Department::select('id', 'name', 'manager_id')->with('manager')->get();

        $employes = Employee::select('id', 'full_name')->get();

        $data = [
            'employee' => $employee,
            'jobs' => $jobs,
            'departments' => $departments,
            'employes' => $employes,
        ];

        return view("admin.employee.edit", compact('data') );
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EmployeeRequest::class);

        CRUD::field('full_name');
        CRUD::field('email')->type('email');
        CRUD::field('phone_number');

        CRUD::field('national_id');
        CRUD::field('birthday')->type('date');
        CRUD::field('location');
        CRUD::addField([
            'name'        => 'gender',
            'label'       => 'Gender',
            'type'        => 'select_from_array',
            'options'     => [
                'male' => 'male',
                'female' => 'female',
            ],
            'default'     => 'male'
        ],);

        CRUD::field('hire_date')->type('date');
        CRUD::field('contract_period');
        CRUD::addField([
            'label'     => "Job",
            'type' => 'select',
            'name' => 'job_id',
            'entity'    => 'job',
            'attribute' => 'title',
            'model'     => "App\Models\Job",
        ]);
        CRUD::addField([
            'name' => 'salary',
            'type' => 'number',
            'attributes' => [
                "step" => "any",
                'min' => 1000,
            ],
            'prefix' => "L.E",
        ]);

        CRUD::addField([
            'label'     => "Department",
            'type' => 'select',
            'name' => 'department_id',
            'entity'    => 'department',
            'attribute'    => 'name',
            'model'     => "App\Models\Department",
        ]);

        CRUD::addField([
            'name'        => 'top_management',
            'label'       => 'Top management',
            'type'        => 'select_from_array',
            'options'     => [
                'ceo' => 'ceo',
                'operation director' => 'operation director',
                'manager' => 'manager',
                'employee' => 'employee',
            ],
            'default'     => 'employee'
        ]);

        CRUD::addField([
            'name'        => 'grades',
            'label'       => 'Grade',
            'type'        => 'select_from_array',
            'options'     => [
                'junior' => 'junior',
                'associate' => 'associate',
                'senior' => 'senior',
            ],
            'default'     => 'junior'
        ]);

        CRUD::addField([
            'name'        => 'role',
            'label'       => 'Role',
            'type'        => 'select_from_array',
            'options'     => [
                'manager' => 'manager',
                'employee' => 'employee',
            ],
            'default'     => 'employee'
        ],);


        CRUD::addField([
            'label'     => "Manager",
            'type' => 'select',
            'name' => 'manager_id',
            'entity'    => 'manager',
            'model'     => "App\Models\Employee",
        ]);


    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {

    }


    /**
     * Define what happens when the Store operation is loaded.
     *
     * @param $request
     * @return Response
     */
    // public function store(EmployeeRequest $request)
    // {
    //     $request->except(['_save_action', '_http_referrer']);
    //     $employee = $this->employeeInterface->create($request->all());

    //     $redirectManager_obj = new RedirectManager();
    //     $redirect_url =  $redirectManager_obj->redirectWithAction(
    //                                                 'save_and_preview',
    //                                                 $request->input('_http_referrer'),
    //                                                 $employee->id,
    //                                             );


    //     return redirect($redirect_url);
    // }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @param $request
     * @return Response
     */
    // public function update(EmployeeRequest $request)
    // {



    //     // $this->crud->removeAllButtons();

    //     // Optionally, you can also remove the button from a specific tab
    //     // $this->crud->removeButtonFromStack('save_and_edit', 'bottom');
    //     // $this->crud->removeButtonFromStack('save_and_edit', 'bottom');
    //     // $this->setupCreateOperation();
    //     // return parent::update($request);
    // }


}
