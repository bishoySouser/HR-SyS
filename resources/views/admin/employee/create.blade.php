@extends(backpack_view('blank'))


@php
  $breadcrumbs = [
      'Admin' => backpack_url('dashboard'),
      'Employes' => backpack_url('employee'),
      'Add' => false,
  ];

  $saveAction = [
        'name' => 'save_action_one',
        'redirect' => function($crud, $request, $itemId) {
            // Define the redirect URL here
            return $crud->route;
        },
        'button_text' => 'Custom save message', // Override text appearing on the button
        'visible' => function($crud) {
            // Customize when this save action is visible for the current operation
            return true;
        },
        'referrer_url' => function($crud, $request, $itemId) {
            // Override http_referrer_url
            return $crud->route;
        },
        'order' => 1, // Change the order save actions are in
    ];

@endphp

@section('content')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">employes</span>
            <small>Add employes. </small>
            <small><a href="{{backpack_url('employee')}}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> Back to all  <span>employes</span></a></small>
        </h2>
  </section>

  <div id="employee-create" class="container-fluid animated fadeIn">
    <div class="row">
        <div class="col-md-8 bold-labels">
            @{{hello}}
            <form method="post" action="{{url('admin/employee')}}">
                @csrf
                <input type="hidden" name="_http_referrer" value="{{ url()->previous() }}">

                <div class="card">
                    <div class="card-body row">
                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="first_name" bp-field-type="text">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="last_name" bp-field-type="text">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="email" bp-field-type="email">
                            <label>Email</label>
                            <input type="email" name="email" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="phone_number" bp-field-type="tel">
                            <label>Phone Number</label>
                            <input type="tel" name="phone_number" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="hire_date" bp-field-type="date">
                            <label>Hire Date</label>
                            <input type="date" name="hire_date" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="job_id" bp-field-type="select">
                            <label>Job ID</label>
                            <select name="job_id" class="form-control">
                                <option value="">-</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="salary" bp-field-type="number">
                            <label>Salary</label>
                            <input type="number" name="salary" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="manager_id" bp-field-type="select">
                            <label>Manager</label>
                            <select name="manager_id" class="form-control">
                                <option value="">-</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="manager_id" bp-field-type="select">
                            <label>Username</label>
                            <input type="text" name="username" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="username" bp-field-type="select">
                            <label>Username</label>
                            <input type="text" name="username" value="" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 required" element="div" bp-field-wrapper="true" bp-field-name="password" bp-field-type="select">
                            <label>Password</label>
                            <input type="password" name="password" value="" class="form-control">
                        </div>

                        <div id="saveActions" class="form-group">

                            <input type="hidden" name="_save_action" value="save_and_back">
                                        <div class="btn-group" role="group">
                            
                            <button type="submit" class="btn btn-success">
                                <span class="la la-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                <span data-value="save_and_back">Save and back</span>
                            </button>
                    
                            <div class="btn-group" role="group">
                                                <button id="bpSaveButtonsGroup" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">▼</span></button>
                                    <div class="dropdown-menu" aria-labelledby="bpSaveButtonsGroup">
                                                        <button type="button" class="dropdown-item" data-value="save_and_edit">Save and edit this item</button>
                                                        <button type="button" class="dropdown-item" data-value="save_and_new">Save and new item</button>
                                                        <button type="button" class="dropdown-item" data-value="save_and_preview">Save and preview</button>
                                                    </div>
                                        </div>
                    
                                        </div>
                            
                                        <a href="http://127.0.0.1:8000/admin/department" class="btn btn-default"><span class="la la-ban"></span> &nbsp;Cancel</a>
                            
                                </div>
                    </div>
                </div>
            </form>
        </div>

@endsection

@push("after_scripts")
<script>
    const employeeCreate = new Vue({
        el: '#employee-create',
        data: {  
            hello: 'sds'
        
        },computed:{
        
        },
        methods:{
        }
        
    });
</script>
@endpush