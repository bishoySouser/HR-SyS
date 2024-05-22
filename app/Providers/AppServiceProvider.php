<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Event;
use App\Observers\EmployeeObserver;
use App\Observers\EventObserver;
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

        // Employee::observe(EmployeeObserver::class);
        // Event::observe(EventObserver::class);
    }
}
