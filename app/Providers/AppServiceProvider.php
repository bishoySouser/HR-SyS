<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Event;
use App\Models\Excuse;
use App\Observers\EmployeeObserver;
use App\Observers\EventObserver;
use App\Models\Vacation;
use App\Observers\VacationObserver;
use App\Models\WorkFromHome;
use App\Observers\ExcuseObserver;
use App\Observers\WorkFromHomeObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // parent::boot();

        Employee::observe(EmployeeObserver::class);
        Event::observe(EventObserver::class);
        Vacation::observe(VacationObserver::class);
        WorkFromHome::observe(WorkFromHomeObserver::class);
        Excuse::observe(ExcuseObserver::class);
    }
}
