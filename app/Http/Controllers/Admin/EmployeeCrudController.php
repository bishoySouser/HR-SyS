<?php

namespace App\Http\Controllers\Admin;

use App\Abstracts\RedirectManager;
use App\Http\Requests\EmployeeRequest;
use App\Jobs\ResetPassword;
use App\Models\Department;
use App\Models\Employee;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Repositories\Employee\EmployeeInterface;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordEmployeeNotification as ResetPasswortNotify;
use App\Models\Job;
use App\Notifications\WelcomeJoin;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
    use \App\Traits\CrudPermissionTrait;

    protected $employeeInterface;
    private $resetPasswordService;

    public function __construct(EmployeeInterface $employeeInterface, ResetPassword $resetPasswordService)
    {
        parent::__construct();

        $this->employeeInterface = $employeeInterface;
        $this->resetPasswordService = $resetPasswordService;
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

        CRUD::column('id');
        CRUD::column('full_name');
        CRUD::column('profile_pic')->type('image');
        CRUD::column('hire_date');
        CRUD::column('manager_id');
        CRUD::column('department_id');
        CRUD::column('updated_at');
        CRUD::column('created_at');


        CRUD::button('resetPassword')->stack('line')->view('crud::buttons.reset-password');
        // CRUD::addButtonFromView('top', $name, $view, $position);


        CRUD::setOperationSetting('lineButtonsAsDropdown', true);



        // $this->crud->addButtonFromModelFunction('line', 'open_google', 'openVacations', 'end');
    }

    /**
     * Define what happens when the list operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();

        // $this->crud->addButtonFromModelFunction('line', 'upload_attach', 'uploadAttachButton', 'beginning');

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

        CRUD::addField([   // Upload
            'name'      => 'profile_pic',
            'label'     => 'picture',
            'type'      => 'upload',
            'upload'    => true,
            'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
        ],);

        CRUD::field('phone_number');

        CRUD::field('national_id')->label('National ID');
        CRUD::field('birthday')->type('date')->label('Birthday Date');
        CRUD::field('location');
        CRUD::field('eduction');
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

        CRUD::field('hire_date')->type('date')->label('Hiring Date');

        CRUD::addField([
            'name'        => 'contract_periods',
            'label'       => 'Contract Type',
            'type'        => 'select_from_array',
            'options'     => [
                'fixed-term contract' => 'fixed-term contract',
                'indefinite/termless contract' => 'indefinite/termless contract',
                'renewable contract' => 'renewable contract',
                'evergreen contract' => 'evergreen contract',
                'month-to-month contract' => 'month-to-month contract',
                'project-based contract' => 'project-based contract',
                'performance-based contract' => 'performance-based contract',
                'trial/probationary period' => 'trial/probationary period',
                'fixed-price contract' => 'fixed-price contract',
                'milestone-based contract' => 'milestone-based contract',
                'lease agreement period' => 'lease agreement period',
                'license agreement period' => 'license agreement period',
            ],
            'default'     => 'male'
        ],);

        CRUD::addField([
            'label'     => "Job",
            'type' => 'select',
            'name' => 'job_id',
            'entity'    => 'job',
            'attribute' => 'title',
            'model'     => "App\Models\Job",
            'options'   => function ($query) {
                // Modify the query to get the muted array of jobs (title and name)
                // print_r($query->get());
                // dd('--');
                return $query->get(['id', \DB::raw('CONCAT(upper(grades), " ", lower(title)) as title')]);
            },
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

        $this->setupCreateOperation();

        $this->crud->replaceSaveActions([
            'name' => 'save_and_preview',
            'visible' => function($crud) {
                return $crud->hasAccess('update');
            },
            'redirect' => function($crud, $request, $itemId) {
                // Return the dynamically determined redirect URL
                return $crud->route . '/' . $itemId . '/show';
            },
        ]);


        // $this->crud->removeButtonFromStack('save_and_edit', 'bottom');

    }

    public function resetPassword(Request $request)
    {

        // Reset the password
        $employeeId = $request->id;
        $this->resetPasswordService->resetEmployeePassword($employeeId);

        // Notify the employee that
        $employee = $this->resetPasswordService->getEmployee();
        $randomPassword = $this->resetPasswordService->getRandomPassword();
        Notification::send($employee, new ResetPasswortNotify($employee, $randomPassword));

        return response()->json([
                                    'message' => 'The new password has been changed and sent to the employee.'
                                ]
                                ,200);
    }

    /**
     * @param int $length
     * @return string $password
     */
    private function randomPassword(int $length = 8)
    {
        $length = 12;  // Set a minimum password length
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password =  substr(str_shuffle($chars),0,$length);
        $password = base64_encode($password);  // Encode to a string

        return $password;
    }

}
