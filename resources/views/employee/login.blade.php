@extends('layouts.app')
@section('pageTitle','Login')
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
    <style>
        .login-img{
            max-width:100%;
            max-height:100%;
            vertical-align: middle; 
        }
    </style>
@stop
@section("content")
<div class="container mb-5">
    <div class="row">
                <div class="col-md-6 col-12">
                    <img src="{{asset('images/employee.jpg')}}" class="mx-auto login-img" alt="Financial Growth"/> 
                </div>
        <div class="col-md-6 col-12">
            <h1 class="mb-3 text-center"><strong><i>LOGIN</i></strong></h1>
            <div class="form p-4">
                @if($errors->any())
                    <div class="alert error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="login-form" method="post" action="{{route('verify-employee')}}">
                    {{ csrf_field() }}
                    <div class="form-group mt-3">
                        <label for="pan">Your PAN:</label>
                        <input type="text" class="form-control" name="pan" id="pan" maxlength=10 required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" id="password" minlength=8 maxlength=20 required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="submit" class="btn btn-outline" value="Login">
                    </div>
                </form>
            </div>
            <div class="login-link text-center my-5">
                <strong>
                    <a href="{{route('forgot-password')}}">Forgot Password</a>
                </strong>
            </div>
        </div>
    </div>    
</div>


@stop