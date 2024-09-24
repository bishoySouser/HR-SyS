<?php

namespace App\Services;

use App\Models\BestManagerInCompany;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BestManagerInCompanyService
{
    private Employee $employee;
    private Employee $manager;
    private Carbon $voteDate;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setManager(Employee $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    public function getManager(): Employee
    {
        return $this->manager;
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

    public function hasEmployeeVotedThisMonth(): bool
    {
        return BestManagerInCompany::where('employee_id', $this->employee->id)
            ->whereYear('vote_date', $this->voteDate->year)
            ->whereMonth('vote_date', $this->voteDate->month)
            ->exists();
    }

    /**
     * Vote for the best manager in the company
     *
     * @return BestManagerInCompany|false
     */
    public function voteForBestManager()
    {
        $this->setVoteDate();

        if ($this->hasEmployeeVotedThisMonth()) {
            throw new \Exception('You have already voted for this month');
        }

        if (!$this->validateEmployeeAndManager()) {
            throw new \Exception("The employee can't vote for themselves or for non-managers.");
        }

        $existingVote = $this->getVoteForMonth();

        if ($existingVote) {
            $existingVote->manager_id = $this->manager->id;
            $existingVote->vote_date = $this->voteDate;
            $existingVote->save();
            return $existingVote;
        }

        return BestManagerInCompany::create([
            'employee_id' => $this->employee->id,
            'manager_id' => $this->manager->id,
            'vote_date' => $this->voteDate,
        ]);
    }

    /**
     * Get the best manager vote for a specific month
     *
     * @return BestManagerInCompany|null
     */
    public function getVoteForMonth()
    {
        return BestManagerInCompany::where('employee_id', $this->employee->id)
            ->whereYear('vote_date', $this->voteDate->year)
            ->whereMonth('vote_date', $this->voteDate->month)
            ->first();
    }

    /**
     * Get all votes for a given month
     *
     * @param string $monthYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getVotesForMonth(string $monthYear)
    {
        $date = Carbon::parse($monthYear);
        return BestManagerInCompany::whereYear('vote_date', $date->year)
            ->whereMonth('vote_date', $date->month)
            ->get();
    }

    /**
     * Get the manager with the most votes for a given month
     *
     * @param string $monthYear
     * @return Employee|null
     */
    public function getBestManagerOfTheMonth(string $monthYear)
    {
        $date = Carbon::parse($monthYear);
        return DB::table('best_manager_in_company')
            ->select('manager_id', DB::raw('COUNT(*) as vote_count'))
            ->whereYear('vote_date', $date->year)
            ->whereMonth('vote_date', $date->month)
            ->groupBy('manager_id')
            ->orderByDesc('vote_count')
            ->first();
    }

    /**
     * Validate that the employee is not voting for themselves and the manager is actually a manager
     *
     * @return bool
     */
    private function validateEmployeeAndManager(): bool
    {
        return $this->employee->manager_id !== $this->manager->id && $this->manager->isManager();
    }
}
