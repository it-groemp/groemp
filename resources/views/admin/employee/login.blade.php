@extends("admin.layouts.app")
@section("pageTitle","Admin Login")
@section("css")
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section("content")
<div class="container box mb-5">
    <h1 class="mb-3 text-center"><strong><i>LOGIN</i></strong></h1>
    <div class="form p-4">
        @if ($errors->any())
            <div class="alert error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="login-form" method="post" action="{{route('verify-admin')}}">
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
                <button type="submit" class="btn btn-outline">Login</button>
            </div>
        </form>
    </div>
    <div class="login-link text-center my-5">
        <strong>
            <a href="{{route('forgot-password-admin')}}">Forgot Password</a>
        </strong>
    </div>
</div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script>
        $("#login-form").validate({
            rules:{
                pan: {
                    checkPan: true
                },
                password: {
                    checkPassword: true
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $.validator.addMethod("checkPan", function (value, elem) {
                var re = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                return re.test(value);
            },
            "Please enter a valid PAN"
        );

        $.validator.addMethod("checkPassword", function (value, elem) {
                var re = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/;
                return re.test(value);
            },
            "Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters"
        );
    </script>
@stop