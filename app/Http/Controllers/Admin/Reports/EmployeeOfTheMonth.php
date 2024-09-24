<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Employee;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeOfTheMonth extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Best Managers
        $this->data['bestManagers'] = DB::table('best_manager_in_company as bm')
            ->join('employes as e', 'bm.manager_id', '=', 'e.id')
            ->select('e.full_name as manager_name', DB::raw('COUNT(bm.manager_id) as total_votes'))
            ->whereBetween('bm.vote_date', [$startDate, $endDate])
            ->groupBy('bm.manager_id', 'e.full_name')
            ->orderBy('total_votes', 'desc')
            ->take(5)
            ->get();

        // Best Employees by Department
        $this->data['bestEmployeesByDepartment'] = DB::table('best_employee_in_team as be')
            ->join('employes as e', 'be.employee_id', '=', 'e.id')
            ->join('departments as d', 'e.department_id', '=', 'd.id')
            ->select(
                'd.name as department_name',
                'e.full_name as employee_name',
                DB::raw('COUNT(be.employee_id) as vote_count')
            )
            ->whereBetween('be.vote_date', [$startDate, $endDate])
            ->groupBy('d.id', 'd.name', 'e.id', 'e.full_name')
            ->orderBy('d.name')
            ->orderBy('vote_count', 'desc')
            ->get()
            ->groupBy('department_name')
            ->map(function ($group) {
                return $group->first(); // Get the top employee for each department
            });

        // Employee of the Month count
        $this->data['employeesOfTheMonth'] = DB::table('employee_of_the_month as eom')
            ->join('employes as e', 'eom.employee_id', '=', 'e.id')
            ->select('e.full_name as employee_name', DB::raw('COUNT(eom.employee_id) as times_chosen'))
            ->whereBetween('eom.month', [$startDate, $endDate])
            ->groupBy('eom.employee_id', 'e.full_name')
            ->orderBy('times_chosen', 'desc')
            ->get();

        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;

        return view(backpack_view('chart-eom'), $this->data);
    }
}
