@extends('layouts.app')
@section('pageTitle','Login')
@section("content")
    <div class="container-fluid">
        <div class="who-are-you py-5">
            <div class="row">
                <h1 class="mb-3 text-center section-title">Employee or Employer? Let us know...</h1>
                <div class="col-md-1 col-12"></div>
                <div class="col-md-4 col-12">
                    <div id="person-box-employee" class="text-center">
                        <div id="employee" class="text-center"><img src="{{asset('images/who-you-are/employee.jpg')}}" alt="Employee"/></div>
                        <h1 class="py-3"><b>Employee</b></h1>
                        <p>Login here to enter the world of benefits</p>
                        <br/>
                    </div>
                </div>
                <div class="col-md-2 col-12"></div>
                <div class="col-md-4 col-12">
                    <div id="person-box-company" class="text-center">
                        <div id="company"><img src="{{asset('images/who-you-are/company.jpg')}}" alt="Company"/></div>
                        <h1 class="py-3"><b>Company</b></h1>
                        <p>Login here to dive into the world of benefits management</p>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section("js")
    <script>
        $("#person-box-employee").click(function(){
            window.location="/employee-login";
        });

        $("#person-box-company").click(function(){
            window.location="/admin/login";
        });
    </script>
@stop