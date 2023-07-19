{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('department') }}"><i class="nav-icon la la-building"></i> Departments</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('job') }}"><i class="nav-icon la la-briefcase"></i> Jobs</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('employee') }}"><i class="nav-icon la la-male"></i> Employes</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('vacation-balance') }}"><i class="nav-icon la la-question"></i> Vacation balances</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('vacation') }}"><i class="nav-icon la la-question"></i> Vacations</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('attendance') }}"><i class="nav-icon la la-question"></i> Attendances</a></li>