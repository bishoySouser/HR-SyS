@extends(backpack_view('blank'))

@section('after_styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Date Range Filter</div>
            <div class="card-body">
                <form id="dateFilterForm" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="start_date" class="mr-2">Start Date:</label>
                        <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ $startDate }}">
                    </div>
                    <div class="form-group mr-2">
                        <label for="end_date" class="mr-2">End Date:</label>
                        <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<p class="text-monospace h1">Employees of the Month</p>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Employees of the Month</div>
            <div class="card-body">
                <canvas id="employeesOfTheMonthChart"></canvas>

            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Times Chosen as Employee of the Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employeesOfTheMonth as $employee)
                        <tr>
                            <td>{{ $employee->employee_name }}</td>
                            <td>{{ $employee->times_chosen }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<p class="text-monospace h1">Voting</p>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Best Managers</div>
            <div class="card-body">
                <canvas id="bestManagersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Best Employees by Department</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Best Employee</th>
                            <th>Vote Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bestEmployeesByDepartment as $department => $employee)
                            <tr>
                                <td>{{ $department }}</td>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->vote_count }}</td>
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
    // Initialize Flatpickr date pickers
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d"
    });

    // Chart initialization functions
    function initBestManagersChart() {
        new Chart(document.getElementById('bestManagersChart'), {
            type: 'bar',
            data: {
                labels: @json($bestManagers->pluck('manager_name')),
                datasets: [{
                    label: 'Total Votes',
                    data: @json($bestManagers->pluck('total_votes')),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Best Managers'
                    }
                }
            }
        });
    }

    function initEmployeesOfTheMonthChart() {
        new Chart(document.getElementById('employeesOfTheMonthChart'), {
            type: 'bar',
            data: {
                labels: @json($employeesOfTheMonth->pluck('employee_name')),
                datasets: [{
                    label: 'Times Chosen as Employee of the Month',
                    data: @json($employeesOfTheMonth->pluck('times_chosen')),
                    backgroundColor: 'rgba(255, 159, 64, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Employees of the Month'
                    }
                }
            }
        });
    }

    // Initialize charts
    initBestManagersChart();
    initEmployeesOfTheMonthChart();

    // Handle form submission
    document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        window.location.href = `{{ route('employee.of.month.report') }}?start_date=${startDate}&end_date=${endDate}`;
    });
</script>
@endsection
