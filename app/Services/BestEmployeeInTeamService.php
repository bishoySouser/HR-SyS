<?php

namespace App\Services;

use App\Models\BestEmployeeInTeam;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BestEmployeeInTeamService
{
    private Employee $manager;
    private Employee $employee;
    private Carbon $voteDate;

    public function setManager(Employee $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    public function getManager(): Employee
    {
        return $this->manager;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setVoteDate(): self
    {
        $this->voteDate = Carbon::now()->startOfMonth();
        return $this;
    }

    public function getVoteDate(): Carbon
    {
        return $this->voteDate;
    }

    public function hasManagerVotedThisMonth(): bool
    {
        // Check if the manager has already voted in the current month
        return BestEmployeeInTeam::where('manager_id', $this->manager->id)
            ->whereYear('vote_date', $this->voteDate->year)
            ->whereMonth('vote_date', $this->voteDate->month)
            ->exists();
    }

    /**
     * Vote for the best employee in the team
     *
     * @return BestEmployeeInTeam|false
     */
    public function voteForBestEmployee()
    {
        $this->setVoteDate();
        // Check if the manager has already voted this month
        if ($this->hasManagerVotedThisMonth()) {
            throw new \Exception('Manager has already voted this month.'); // Manager has already voted, no further votes allowed this month
        }

        if (!$this->validateManagerAndEmployee()) {
            throw new \Exception("The manager isn't direct manager for this employee .");
        }

        // Check if a vote already exists for this month
        $existingVote = $this->getVoteForMonth();

        if ($existingVote) {
            $existingVote->employee_id = $this->employee->id;
            $existingVote->vote_date = $this->voteDate;
            $existingVote->save();
            return $existingVote;
        }

        // Create new vote
        return BestEmployeeInTeam::create([
            'manager_id' => $this->manager->id,
            'employee_id' => $this->employee->id,
            'vote_date' => $this->voteDate,
        ]);
    }

    /**
     * Get the best employee vote for a specific month
     *
     * @return BestEmployeeInTeam|null
     */
    public function getVoteForMonth()
    {
        return BestEmployeeInTeam::where('manager_id', $this->manager->id)
            ->whereYear('vote_date', $this->voteDate->year)
            ->whereMonth('vote_date', $this->voteDate->month)
            ->first();
    }

    /**
     * Get all votes for a specific department in a given month
     *
     * @param string $monthYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVotesForDepartmentAndMonth(string $monthYear)
    {
        $date = Carbon::parse($monthYear);
        return BestEmployeeInTeam::whereYear('vote_date', $date->year)
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
     * @return bool
     */
    private function validateManagerAndEmployee(): bool
    {
        return $this->employee->manager_id === $this->manager->id;
    }
}
