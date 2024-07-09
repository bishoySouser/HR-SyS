<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Excuse;
use App\Models\Holiday;
use App\Models\Vacation;
use App\Models\WorkFromHome;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->query('date') ?? today()->format('Y-m-d');

        $events = Event::whereDate('date', $date)
            ->get()
            ->map(function ($event) {
                return $event->name;
            });

        $employees_have_vacation = Vacation::with('balance.employee')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->where('status', 'hr_approved')
            ->get()
            ->map(function ($vacation) {
                return [
                    'picProfile' => $vacation->balance->employee->profile_pic,
                    'name' => $vacation->balance->employee->fname,

                ];
            });

        $holidays = Holiday::select('name')->where(function ($query) use ($date) {
                    $query->where('from_date', '<=', $date)
                            ->where('to_date', '>=', $date);
                })->first();

        $employees_have_workFromHome = WorkFromHome::with('employee')
            ->where('day', $date)
            ->where('status', 'Approved')
            ->get()
            ->map(function ($workFromHome) {
                return [
                    'name' => $workFromHome->employee->fname,
                    'picProfile' => $workFromHome->employee->profile_pic

                ];
            });

        $employees_birthdays = Employee::whereMonth('birthday', '=', date('m', strtotime($date)))
                    ->whereDay('birthday', '=', date('d', strtotime($date)))
                    ->get()
                    ->map(function ($employee) {
                        return [
                            'picProfile' => $employee->profile_pic,
                            'name' => $employee->fname
                        ];
                    });

        $employees_have_excuse = Excuse::with('employee')
                    ->whereDate('date', $date)
                    ->where('status', 'Approved')
                    ->get()
                    ->map(function ($excuse) {
                        return [
                            'name' => $excuse->employee->fname,
                            'picProfile' => $excuse->employee->profile_pic,
                            'type' => $excuse->type
                        ];
                    });

        $data = [
            'events_list' => $events,
            'empVacation' => $employees_have_vacation,
            'holidays' => $holidays,
            'empWorkFromHome' => $employees_have_workFromHome,
            'empBirthdays' => $employees_birthdays,
            'empExcuse' => $employees_have_excuse
        ];

        return response()->json($data);

    }

}
