@extends(backpack_view('blank'))


@php
  $breadcrumbs = [
      'Admin' => backpack_url('dashboard'),
      'Employes' => backpack_url('employee'),
      'Edit' => false,
  ];
@endphp

@section('content')
<section class="container-fluid">
    <h2>
        <span class="text-capitalize">employes</span>
        <small>Edit employes. </small>
        <small><a href="{{backpack_url('employee')}}" class="d-print-none font-sm"><i class="la la-angle-double-left"></i> Back to all  <span>employes</span></a></small>
    </h2>
</section>

<div id="app" class="container-fluid animated fadeIn">
   
            
          

            <employee-edit
                :employee="{{ $data['employee'] }}"
                :jobs="{{ $data['jobs'] }}"
                :departments ="{{ $data['departments'] }}"
                :managers ="{{$data['employes']}}"
                :urls="{
                        previous: '{{url('admin/employee')}}'
                }"
            ></employee-edit>
            
     

@endsection

