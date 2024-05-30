@extends('admin.layouts.app')
@section('pageTitle','Admin Login')
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section("content")
<div class="container box mb-5">
    <h1 class="mb-3 text-center"><strong><i>LOGIN</i></strong></h1>
    <div class="form p-4">
        @if(session("error"))
            <div class="error mb-3">{!!session("error")!!}</div>
        @endif
        <form id="login-form" method="post" action="{{route('verify-admin')}}">
            {{ csrf_field() }}
            <div class="form-group mt-3">
                <label for="pan">Company PAN:</label>
                <input type="text" class="form-control" name="pan" id="pan" maxlength=10 required>
            </div>
            <div class="form-group mt-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" minlength=8 maxlength=20 required>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-outline">Login</button>
            </div>
        </form>
    </div>
    <div class="login-link text-center my-5">
        <strong>
            <a href="{{route('set-password-admin','forgot')}}">Forgot Password</a>
            <br/><br/>
            Don't Have An Password? Click to set it
            <a href="{{route('set-password-admin','set')}}">Set Password</a>
        </strong>
    </div>
</div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script link="{{asset('js/validation-admin.js')}}"></script>
@stop