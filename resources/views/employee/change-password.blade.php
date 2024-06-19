@extends('layouts.app')
@section('pageTitle', 'Change Password')
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container box">        
        <h1 class="mb-3 text-center"><strong><i>Change Password</i></strong></h1>
        @if(session("success"))
            <div class="success mb-3 p-3">{{session("success")}}</div>
        @elseif(session("error"))
            <div class="error mb-3 p-3">{{session("error")}}</div>
        @endif
        @php
            $pan = Session::get("pan");
        @endphp
        <div class="form p-4 mb-5">
            <form id="change-password-form" method="post" action="{{route('update-password')}}">
                {{ csrf_field() }}
                <div class="form-group mt-3">
                    <label for="pan">Your PAN:</label>
                    <input type="text" class="form-control" name="pan" id="pan" maxlength=10 value="{{$pan}}" readonly required>
                </div>
                <div class="form-group mt-3">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" minlength=8 maxlength=20 required>
                </div>
                <div class="form-group mt-3">
                    <label for="cnfm_password">Confirm Password:</label>
                    <input type="password" class="form-control" name="cnfm_password" id="cnfm_password" minlength=8 maxlength=20 required>
                </div>
                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-outline" value="Change Password"/>
                </div>
            </form>
        </div>
    </div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script link="{{asset('js/validation.js')}}"></script>
    <script>
        $("#change-password-form").validate({
            rules:{
                password: {
                    checkPassword: true,
                    equalPassword: true
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });
    </script>
@stop