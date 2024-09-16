<?php

namespace App\Services;

use App\Models\BestEmployeeInTeam;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BestEmployeeInTeamService
{
    /**
     * Vote for the best employee in the team
     *
     * @param int $managerId
     * @param int $employeeId
     * @param int $departmentId
     * @param string $voteDate
     * @return BestEmployeeInTeam|false
     */
    public function voteForBestEmployee(int $managerId, int $employeeId, int $departmentId, string $voteDate)
    {
        // Validate that the manager is in the same department as the employee
        if (!$this->validateManagerAndEmployee($managerId, $employeeId, $departmentId)) {
            return false;
        }

        // Check if a vote already exists for this month
        $existingVote = $this->getVoteForMonth($managerId, $departmentId, $voteDate);

        if ($existingVote) {
            // Update existing vote
            $existingVote->employee_id = $employeeId;
            $existingVote->vote_date = $voteDate;
            $existingVote->save();
            return $existingVote;
        }

        // Create new vote
        // return BestEmployeeInTeam::create([
        //     'manager_id' => $managerId,
        //     'employee_id' => $employeeId,
        //     'department_id' => $departmentId,
        //     'vote_date' => $voteDate,
        // ]);
    }

    /**
     * Get the best employee vote for a specific month
     *
     * @param int $managerId
     * @param int $departmentId
     * @param string $voteDate
     * @return BestEmployeeInTeam|null
     */
    public function getVoteForMonth(int $managerId, int $departmentId, string $voteDate)
    {
        $date = Carbon::parse($voteDate);
        return BestEmployeeInTeam::where('manager_id', $managerId)
            ->where('department_id', $departmentId)
            ->whereYear('vote_date', $date->year)
            ->whereMonth('vote_date', $date->month)
            ->first();
    }

    /**
     * Get all votes for a specific department in a given month
     *
     * @param int $departmentId
     * @param string $monthYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVotesForDepartmentAndMonth(int $departmentId, string $monthYear)
    {
        $date = Carbon::parse($monthYear);
        return BestEmployeeInTeam::where('department_id', $departmentId)
            ->whereYear('vote_date', $date->year)
            ->whereMonth('vote_date', $date->month)
            ->get();
    }

    /**
     * Get the employee with the most votes across all departments for a given month
     *
     * @param string $monthYear
     * @return Employee|null
     */
    public function getBestEmployeeOfTheMonth(string $monthYear)
    {
        $date = Carbon::parse($monthYear);
        return DB::table('best_employee_in_team')
            ->select('employee_id', DB::raw('COUNT(*) as vote_count'))
            ->whereYear('vote_date', $date->year)
            ->whereMonth('vote_date', $date->month)
            ->groupBy('employee_id')
            ->orderByDesc('vote_count')
            ->first();
    }

    /**
     * Validate that the manager is in the same department as the employee
     *
     * @param int $managerId
     * @param int $employeeId
     * @param int $departmentId
     * @return bool
     */
    private function validateManagerAndEmployee(int $managerId, int $employeeId, int $departmentId): bool
    {
        $manager = Employee::find($managerId);
        $employee = Employee::find($employeeId);

        if (!$manager || !$employee) {
            return false;
        }

        return $manager->department_id === $departmentId &&
               $employee->department_id === $departmentId &&
               $employee->manager_id === $managerId;
    }
}
