@extends('layouts.app')
@section('pageTitle','Home')
@section('css')
    <style>
        .home{
             background: #3572EF;
        }

        .problems{
            background: #f8ffbc;
        }

        .problem-box{
            height: 200px;
            border: 5px solid green;
            border-radius: 30px 50px;
        }

        .problems .section-title{
            color:#FF5F1F;
        }
        .problem-box h2{
            color: #FF5F1F;
            font-weight: bolder;
        }
        .problem-box h4{
            font-weight: bold;
        }

        .solutions{
            background: #A7E6FF;
        }

        .sol-img{
            width: 80% !important;
            height: 80% !important;
            display: block;
        }

        .sol-desc{
            font-size: 150% !important;
        }

        .benefits{
            background: #FFFAA0;
        }

        .benefit-box{
            background:white;
        }

        .why-us{
            background: #FDFFE2;
        }

        .reason {
            height: 200px;
            font-size: 150%;
            background: yellow;
            border-radius: 20px;    
        }
    </style>
@stop
@section('content')
    <div class="container-fluid">
        <div class="home">
            <div class="row">
                <div class="col-md-6 col-12">
                    <img src="{{asset('images/financial_growth.jpg')}}" class="mx-auto growth-img" alt="Financial Growth"/> 
                </div>
                <div class="col-md-6 col-12 text-center" style="color: white;">
                    <h1 class="my-5 text-center"><b>Grow Your Wealth<b></h1>
                    <h3><b>Unlock the power to save on your wealth and obtain great benefits with us.​</b></h3>
                </div>
            </div>
        </div>

        <div class="pt-5 problems">
            <h1 class="mb-3 text-center section-title">The Problems</h1>
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="mx-2 px-2 problem-box text-center">
                        <h2 class="mt-2">Lower net pay</h2>
                        <h4>Employees get low net pay as compared to gross pay due to deduction of tax</h4>
                    </div>    
                </div>
                <div class="col-md-4 mb-5">
                    <div class="mx-2 px-2 problem-box text-center">
                        <h2 class="mt-2">Benefits not extended to all</h2>
                        <h4>All employees cannot avail employee benefits such as car lease, fuel, mobile, etc.</h4>
                    </div>    
                </div>
                <div class="col-md-4 mb-5">
                    <div class="mx-2 px-2 problem-box text-center">
                        <h2 class="mt-2">Benefits management is chaotic</h2>
                        <h4>Need an entire team to manage employee benefits along with proper system and tools</h4>
                    </div>    
                </div>
            </div>
        </div>

        <div class="py-5 solutions">
            <h1 class="mb-3 text-center section-title">Our Solutions...</h1>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 mt-4">
                    <div class="mx-2">
                        <img src="{{asset('images/solutions/incentive.jpg')}}" class="mx-auto pb-2 sol-img" alt="Incentive without incentive"/>
                        <p class="text-center sol-desc">By availing Groemp services, employee will receive upto 30% incentive by saving against vouchers availed</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mt-4">
                    <div class="mx-2">
                        <img src="{{asset('images/solutions/employees.jpg')}}" class="mx-auto pb-2 sol-img" alt="Incentive without incentive"/>
                        <p class="text-center sol-desc">Benefit of savings upto 30% can be extended to all employees as agreed by management</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mt-4">
                    <div class="mx-2">
                        <img src="{{asset('images/solutions/hr.jpg')}}" class="mx-auto pb-2 sol-img" alt="Incentive without incentive"/>
                        <p class="text-center sol-desc">HR can focus on core work and Groemp will manage the benefits with their technology and systems</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5 benefits">
            <h1 class="mb-3 text-center section-title">Benefits You Can Avail With Us</h1>
            <div class="row">
                @foreach($categories as $category)
                    @php
                        $image = $category->image_name
                    @endphp
                    <div class="span3 col-lg-3 col-md-6 col-12 mt-4">
                        <div class="benefit-box border">
                            <img src="{{asset('images/categories/'.$image)}}" class="p-2 mx-auto category-img" alt="{{$category->name}}"/>
                            <h4 class="text-center"><b>{{$category->name}}</b></h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="who-are-you py-5">
            <div class="row">
                <h1 class="mb-3 text-center section-title">Who are you</h1>
                <div class="col-md-1 col-12"></div>
                <div class="col-md-4 col-12">
                    <div id="person-box-employee" class="text-center">
                        <div id="employee" class="text-center"><img src="{{asset('images/who-you-are/employee.jpg')}}" alt="Employee"/></div>
                        <h1 class="py-3"><b>Employee</b></h1>
                        <p>Login here to enter the world of benefits</p>
                        <br/><br/>
                    </div>
                </div>
                <div class="col-md-2 col-12"></div>
                <div class="col-md-4 col-12">
                    <div id="person-box-company" class="text-center">
                        <div id="company"><img src="{{asset('images/who-you-are/company.jpg')}}" alt="Company"/></div>
                        <h1 class="py-3"><b>Company</b></h1>
                        <p>Login here to dive into the world of benefits management</p>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>

        <div class="why-us py-5">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3 text-center section-title">Why Us</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>One stop solution provider <br/>for all your employee needs</span>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>You choose what benefits you wish to avail and to what extent</span>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>Free Tax consultation for first 1000 employees</span>
                    </div>
                </div>
                <div class="px-4 pt-2 pb-5 text-right" style="background: #FDFFE2; font-size:150%">
                    <span>and many more....</span>
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