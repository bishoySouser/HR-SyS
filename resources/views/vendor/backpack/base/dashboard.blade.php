@extends(backpack_view('blank'))

@section('after_styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')

<div class="row">

    <div class="col-sm-6 col-md-2">
      <div class="card text-white bg-dark">
        <div class="card-body">
          <div class="h1 text-muted text-right mb-4"><i class="icon-people"></i></div>
          <div class="text-value">{{ $departments_count }}</div><small class="text-muted text-uppercase font-weight-bold">Departments</small>

        </div>
      </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-md-2">
      <div class="card text-white bg-success">
        <div class="card-body">
          <div class="h1 text-muted text-right mb-4"><i class="icon-user-follow"></i></div>
          <div class="text-value">{{ $jobs_count }}</div><small class="text-muted text-uppercase font-weight-bold">Jobs</small>

        </div>
      </div>
    </div>
    <!-- /.col-->
    <div class="col-sm-6 col-md-2">
      <div class="card text-white bg-warning">
        <div class="card-body">
          <div class="h1 text-muted text-right mb-4"><i class="icon-basket-loaded"></i></div>
          <div class="text-value">{{ $employees_count }}</div><small class="text-muted text-uppercase font-weight-bold">Employees</small>

        </div>
      </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        charts
    </div>
</div>

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{count($newcomers)}} Recently Joined</div>
        <div class="card-body p-0">
          <table class="table table-responsive-sm table-striped mb-0">
            <thead class="thead-light">
              <tr>

                <th>Name</th>
                <th class="text-center">Email</th>
                <th class="text-center">Department</th>

              </tr>
            </thead>
            <tbody>
                @foreach ($newcomers as $item)
                    <tr>
                        <td>
                           <a href="{{ route('employee.show', [$item->id]) }}" target="_blank"> <div> {{ $item->full_name }} </div> </a>
                            <div class="small text-muted">Hire date: {{ $item->hire_date_format }}</div>
                        </td>
                        <td class="text-center">
                            <div>{{ $item->email }}</div>
                        </td>
                        <td class="text-center">
                            <div>{{ $item->department->name }}</div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>

<div class="row">
    <!-- Your existing cards here -->
</div>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Salary Ranges by Job Title</div>
            <div class="card-body">
                <canvas id="salaryRangesChart"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Remaining Leave Days (Top 10 Employees)</div>
            <div class="card-body">
                <canvas id="remainingLeaveDaysChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Vacation Request Status Distribution</div>
            <div class="card-body">
                <canvas id="vacationStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Monthly Vacation Days Taken</div>
            <div class="card-body">
                <canvas id="monthlyVacationDaysChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Average Vacation Duration by Month</div>
            <div class="card-body">
                <canvas id="avgVacationDurationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Top 5 Best Managers</div>
        <div class="card-body p-0">
          <table class="table table-responsive-sm table-striped mb-0">
            <thead class="thead-light">
              <tr>
                <th>Manager Name</th>
                <th class="text-center">Vote Date</th>
                <th class="text-center">Total Votes</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($bestManagers as $manager)
                    <tr>
                        <td>{{ $manager->manager_name }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($manager->vote_date)->format('F, Y') }}</td>
                        <td class="text-center">{{ $manager->total_votes }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>


@endsection

@section('after_scripts')
<script>
    // Department Distribution Chart


    // Salary Range Distribution Chart
    new Chart(document.getElementById('salaryChart'), {
        type: 'bar',
        data: {
            labels: @json($salaryLabels),
            datasets: [{
                label: 'Number of Jobs',
                data: @json($salaryData),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            title: {
                display: true,
                text: 'Salary Range Distribution'
            }
        }
    });

    new Chart(document.getElementById('salaryRangesChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($salaryRangesByJob->pluck('title')) !!},
            datasets: [{
                label: 'Min Salary',
                data: {!! json_encode($salaryRangesByJob->pluck('min_salary')) !!},
                backgroundColor: '#36A2EB'
            }, {
                label: 'Max Salary',
                data: {!! json_encode($salaryRangesByJob->pluck('max_salary')) !!},
                backgroundColor: '#FF6384'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('remainingLeaveDaysChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($remainingLeaveDays->pluck('employee.fname')) !!},
            datasets: [{
                label: 'Remaining Leave Days',
                data: {!! json_encode($remainingLeaveDays->pluck('remaining_days')) !!},
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Vacation Status Distribution Chart
    new Chart(document.getElementById('vacationStatusChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($vacationStatusDistribution->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($vacationStatusDistribution->pluck('count')) !!},
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
            }]
        }
    });

    // Monthly Vacation Days Chart
    new Chart(document.getElementById('monthlyVacationDaysChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyVacationDays->pluck('month')) !!},
            datasets: [{
                label: 'Total Vacation Days',
                data: {!! json_encode($monthlyVacationDays->pluck('total_days')) !!},
                borderColor: '#FF6384',
                fill: false
            }]
        }
    });

    // Average Vacation Duration Chart
    new Chart(document.getElementById('avgVacationDurationChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($avgVacationDurationByMonth->pluck('month')) !!},
            datasets: [{
                label: 'Average Vacation Duration (Days)',
                data: {!! json_encode($avgVacationDurationByMonth->pluck('avg_duration')) !!},
                backgroundColor: '#4BC0C0'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>


@endsection
