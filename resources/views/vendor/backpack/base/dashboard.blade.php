@extends(backpack_view('blank'))


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
                            <div> {{ $item->full_name }} </div>
                            <div class="small text-muted">Hire date: {{ $item->hire_date }}</div>
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
@endsection
