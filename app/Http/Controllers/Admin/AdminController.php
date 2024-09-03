<?php

namespace  App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Vacation;
use App\Models\VacationBalance;
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
        $data = [
            'departments_count' => Department::count(),
            'jobs_count' => Job::count(),
            'employees_count' => Employee::count(),
            'newcomers' => Employee::select('id', 'full_name', 'department_id', 'email', DB::raw("DATE_FORMAT(hire_date, '%M %d,%Y') as hire_date_format", 'hire_date'))
                                    ->with('department')->orderBy('hire_date', 'desc')->take(5)->get(),
        ];

        // Data for Salary Range Distribution
        $salaryRanges = [
            '0-5000' => Job::whereBetween('max_salary', [0, 5000])->count(),
            '5001-10000' => Job::whereBetween('max_salary', [5001, 10000])->count(),
            '10001-15000' => Job::whereBetween('max_salary', [10001, 15000])->count(),
            '15001+' => Job::where('max_salary', '>', 15000)->count(),
        ];
        $data['salaryLabels'] = array_keys($salaryRanges);
        $data['salaryData'] = array_values($salaryRanges);

        $data['salaryRangesByJob'] = $this->getSalaryRangesByJob();

        $data['remainingLeaveDays'] = $this->getRemainingLeaveDays();
        $data['vacationStatusDistribution'] = $this->getVacationStatusDistribution();
        $data['monthlyVacationDays'] = $this->getMonthlyVacationDays();
        $data['avgVacationDurationByMonth'] = $this->getAvgVacationDurationByMonth();

        // return $data;

        return view(backpack_view('dashboard'), $data);
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

    private function getSalaryRangesByJob()
    {
        return DB::table('jobs')
            ->select('title', 'min_salary', 'max_salary')
            ->get();
    }

    private function getRemainingLeaveDays()
    {
        return VacationBalance::with('employee')
            ->where('year', date('Y'))
            ->orderBy('remaining_days', 'desc')
            ->take(2)
            ->get();
    }

    private function getVacationStatusDistribution()
    {
        return Vacation::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
    }

    private function getMonthlyVacationDays()
    {
        return Vacation::select(
            DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month'),
            DB::raw('SUM(duration) as total_days')
        )
        ->where('status', 'hr_approved')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }

    private function getAvgVacationDurationByMonth()
    {
        return Vacation::select(
            DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month'),
            DB::raw('AVG(duration) as avg_duration')
        )
        ->where('status', 'hr_approved')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }
}
