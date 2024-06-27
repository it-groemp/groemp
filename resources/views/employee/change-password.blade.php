@extends('layouts.app')
@section('pageTitle', 'Change Password')
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container box">        
        <h1 class="mb-3 text-center"><strong><i>Change Password</i></strong></h1>
        @if($errors->any())
            <div class="alert error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $pan = Session::get("pan");
        @endphp        
        <div class="success">
            <ul>
                <li>Password should be minimum 8 characters and maximum 20 characters.</li>
                <li>Password should contain atleast one uppercase, lowercase, one number and one special character.</li>
            </ul>
        </div>
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
    <script>
        $("#change-password-form").validate({
            rules:{
                password: {
                    checkPassword: true,
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $.validator.addMethod("checkPassword", function (value, elem) {
                var re = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/;
                return re.test(value);
            },
            "Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters"
        );
    </script>
@stop