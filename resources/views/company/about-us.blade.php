@extends('layouts.app')
@section('pageTitle','About')
@section('css')
    <style>
        .para{
            border: 4px solid #04bc64;
            font-size: 150%;
            border-radius: 20px 0px;
        }
    </style>
@stop
@section("content")
    <div class="container box mb-5">
        <h1 class="mb-3 text-center"><strong><i>ABOUT US</i></strong></h1>
        <div class="para mb-3 p-4">
            During the process of journey of our founders, we found out that there is need to create 
            the infrastructure which will support India. Employer wants to provide lot of free services 
            to the employees, most of times, they have to take back seat because of effort required 
            from the company side. 
        </div>
        <div class="para mb-3 p-4">
            It is the major requirement in the India, but it is continuing since years. Now the time 
            has changed and technology is there to support the idea. We come up with the automatic 
            tool which not only help organization to improve the standard of life and well being of 
            the employees without any long process for the organization. It also empower employees to 
            select the benefit from the company. 
        </div>
        <div class="para mb-3 p-4">
            Over all basis, the goal is to create win-win situation of everyone where employee remain 
            motivated and employer need not to spend extra money.
        </div>
        <div class="para mb-3 p-4 text-center">
            Founders to help you achieve the same.
            <div class="row mt-3">
                <div class="col-md-4">
                    <img class="mb-4" src="{{asset('images/founders/gyandeep.jpg')}}" width="200px" alt="Gyandeep"/>
                    <p class="text-center"><b>Gyandeep Mittal</b></p>
                    <p>Chief Executive Officer</p>
                </div>
                <div class="col-md-4">
                    <img class="mb-4" src="{{asset('images/founders/pankit.jpg')}}" width="200px" alt="Gyandeep"/>
                    <p class="text-center"><b>Pankit Shah</b></p>
                    <p>Chief Financial Officer</p>
                </div>
                <div class="col-md-4">
                    <img class="mb-4" src="{{asset('images/founders/maneet.jpg')}}" width="200px" alt="Gyandeep"/>
                    <p class="text-center"><b>Maneet Singh</b></p>
                    <p>Chief of Client Relations</p>
                </div>
            </div>
        </div>
    </div>
@stop