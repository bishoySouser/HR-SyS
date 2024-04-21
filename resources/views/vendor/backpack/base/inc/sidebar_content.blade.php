<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-dashboard nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<!-- Employee Section -->
<li class=" nav-header mt-2">Company</li>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('department') }}"><i class="nav-icon la la-building"></i> Departments</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('job') }}"><i class="nav-icon la la-briefcase"></i> Jobs</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('employee') }}"><i class="nav-icon la la-male"></i> Employees</a></li>
</ul>

<!-- Vacation and Attendance Section -->
<li class=" nav-header mt-2">Time Management</li>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('vacation-balance') }}"><i class="nav-icon la la-calendar"></i> Vacation balance</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('vacation') }}"><i class="nav-icon la la-sun-o"></i> Vacations</a></li>
    {{--<li class="nav-item"><a class="nav-link" href="{{ backpack_url('attendance') }}"><i class="nav-icon la la-paw"></i> Attendances</a></li> --}}
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('work-from-home') }}"><i class="nav-icon la la-home"></i> Work from home </a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('excuse') }}"><i class="nav-icon la la la-hand-pointer-o"></i> Excuse </a></li>
</ul>

<li class=" nav-header mt-2">Insurance</li>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('social-insurance') }}"><i class="nav-icon la la la-leaf"></i> Social insurance</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('medical-insurance') }}"><i class="nav-icon la la la-medkit"></i> Medical insurance</a></li>
</ul>

<li class=" nav-header mt-2">Events</li>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-header mt-2"><a class="nav-link" href="{{ backpack_url('event') }}"><i class="nav-icon la la-clock-o"></i> Events</a></li>
</ul>

<li class=" nav-header mt-2">Course</li>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('course') }}"><i class="nav-icon la la-book"></i> Courses</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('enrollment') }}"><i class="nav-icon la la-ticket"></i> Enrollments</a></li>
</ul>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('it-ticket') }}"><i class="nav-icon la la-question"></i> It tickets</a></li>