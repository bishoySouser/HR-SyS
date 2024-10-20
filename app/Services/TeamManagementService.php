<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TeamManagement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TeamManagementService
{
    public function getTeamMembers(Employee $manager): Collection
    {
        return Cache::remember(
            "team_members.{$manager->id}",
            now()->addHours(1),
            fn() => Employee::where('manager_id', $manager->id)->get()
        );
    }
}
