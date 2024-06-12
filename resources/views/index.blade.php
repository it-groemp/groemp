@extends('layouts.app')
@section('pageTitle','Home')
@section('css')
    <style>
        .home{
             background: #FF914D;
             color: black;
        }

        .problems{
            background: #cdf2e0;
        }

        .problem-box{
            height: 200px;
            border: 5px solid green;
            border-radius: 30px 50px;
        }

        .problems .section-title{
            color:#FF5F1F;
        }

        .problems li{
            font-weight:normal;
        }
        
        .problems li::marker{
            content: "₹  ";
            font-size: 20px;
            color: green;
        }

        .benefits{
            background: #dbf0f9;
            display: table;
            width: 100%;
        }

        .benefits h5{
            padding-left: 30px;
            font-weight: bold;
        }

        .benefit-box{
            background:white;
            text-align: justify;
            height: 100%;
        }

        .benefit-box img{            
            float: left;
        }

        .benefit-box p{
            font-weight: normal;
        }

        .benefits .row{
            overflow: hidden; 
        }

        .problems {
            background: #cdf2e0;
        }

        .about-us{
            background: #cdf2e0;
        }

        .reason {
            height: 200px;
            font-size: 150%;
            background: #68c096;
            border-radius: 20px;    
        }

        .about-us a, .about-us a:hover{
            color: #039650;
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
                <div class="col-md-6 col-12 text-center">
                    <br/>
                    <h1 class="my-5 text-center"><b>Value Creation<b></h1>
                    <h3><b>Unlock the power to save on your wealth and obtain great benefits with us.​</b></h3>
                </div>
            </div>
        </div>

        <div class="pt-5 problems">
            <h1 class="mb-3 text-center section-title">The Problems</h1>
            <div class="row mx-5">
                <ul>   
                    <li class="mb-3">
                        Most of companies face the issue at one point of time, it may be starting phase 
                        or it may be later stage when the company grow up <b>significantly.</b> 
                        Challenge is to keep the policy standardize and fit everyone in the world within 
                        same policy.
                    </li>
                    <li class="mb-3">
                        Keep every employee happy with the same standardize solution. Assume that 
                        company has very good HR policy, whether is it possible to keep <b>CEO</b> of the 
                        company and the <b>Executive</b> person happy with the same benefits. CEO may expect 
                        the benefits of <b>Car lease</b> and Driver while executive will be expecting top up 
                        insurance or <b>Transport</b> to the office.
                    </li>
                    <li class="mb-3">
                        There are certain employees, who are lack the reach to certain benefits due to 
                        various factors, it may be correct insurance plan, guidance in <b>tax planning </b>
                        or may be good quality holidays on exotic location.
                    </li>
                </ul>                
            </div>
        </div>

        <div class="py-5 benefits">
            <h1 class="mb-3 text-center section-title">Benefits You Can Avail With Us</h1>
            <div class="row mx-0">
                <h3 class="text-center"><b>Is there any place where all the problems can be addressed??? Currently not.</b></h3>
                <h3 class="text-center mb-4"><b>To build the strong workplace culture, addressing the personal problem is also necessary.</b></h3>
                <h5>Here we take provide these solutions, it can help the companies to find out the solution for their needs.</h5>
                <h5>Have a look on below benefits and try to understand the benefits mentioned below:</h5>
                
                @foreach($categories as $category)
                    @php
                        $image = $category->image_name
                    @endphp
                    <div class="span3 col-md-6 col-12 mt-4">
                        <div class="benefit-box border">                            
                            <img src="{{asset('images/categories/'.$image)}}" width="100px" height="100px" class="p-2" alt="{{$category->name}}"/>
                            <h3 class="text-center mt-2"><b>{{$category->name}}</b></h3>
                            <p class="p-2">{{$category->description}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="who-are-you py-5">
            <div class="row">
                <h1 class="mb-3 text-center section-title">Employee or Employer? Let us know...</h1>
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

        <div class="about-us py-5">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3 text-center section-title">How Can We Help...</h1>
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
                <div class="px-4 pt-2 pb-5 text-right" style="font-size:150%">
                    <a href="{{route('about-us')}}">Know More About Us</a>
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