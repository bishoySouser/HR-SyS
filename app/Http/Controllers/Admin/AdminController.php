<?php

namespace  App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Job;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin')     => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        $departments = Department::count();
        $jobs = Job::count();
        $employees = Employee::count();

        $newcomers = Employee::with('department')
                                ->select('full_name', 'department_id', 'email', DB::raw("DATE_FORMAT(hire_date, '%M %d,%Y') as hire_date"))
                                ->orderBy('hire_date', 'desc')
                                ->take(5)
                                ->get();

        $this->data['departments_count'] = $departments;
        $this->data['jobs_count'] = $jobs;
        $this->data['employees_count'] = $employees;
        $this->data['newcomers'] = $newcomers;

        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }
}
