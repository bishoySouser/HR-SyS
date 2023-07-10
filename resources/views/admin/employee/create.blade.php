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

<div id="app" class="container-fluid animated fadeIn">
   
            
          

            <employee-create
                :jobs="{{ $data['jobs'] }}"
                :departments ="{{$data['departments']}}"
                :managers ="{{$data['employes']}}"
                :urls="{
                    previous: '{{url('admin/employee')}}'
                }"
               
                
            ></employee-create>
            
     

@endsection

