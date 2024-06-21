@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container box my-5" style="width: 70%">
        <h1 class="mb-3 text-center"><strong><i>Add Admin</i></strong></h1>
        @if(session("error"))
            <div class="error mb-3">{!!session("error")!!}</div>
        @endif
        <form id="add-admin-form" method="post" action="{{route('save-admin')}}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" maxlength=50 required>
            </div>
            <div class="form-group mt-3">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="form-group mt-3">
                <label for="mobile">Mobile:</label>
                <input type="tel" class="form-control" name="mobile" id="mobile" pattern="[6-9]{1}[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" minlength=10 maxlength=10 required>
            </div>
            <div class="form-group mt-3">
                <label for="pan">Company PAN:</label>
                <input type="text" class="form-control" name="pan" id="pan" maxlength=10 required>
            </div>
            <div class="form-group mt-3">
                <label for="role" class="form-label">Role:</label>
                <select class="form-control" name="role" id="role" required>
                    <option value="Employer">Employer</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-colored">Save Details</button>
            </div>
        </form>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script>
        $("#add-admin-form").validate({
            rules:{
                name: {
                    alpha: true
                },
                email: {
                    email: true
                },
                mobile: {
                    checkMobile: true
                },
                pan: {
                    checkPan: true
                }
            },
            messages:{
                email: {
                    email: "Please enter a Valid Email Id"
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $.validator.addMethod("alpha", function (value, elem) {
                var re = /^[a-zA-Z .]+$/;
                return re.test(value);
            },
            "Only Capital, Small Letters, Spaces and Dot Allowed"
        );

        $.validator.addMethod("checkMobile", function (value, elem) {
                var re = /[6-9]{1}[0-9]{9}/;
                return re.test(value);
            },
            "Please enter a valid mobile number"
        );

        $.validator.addMethod("checkPan", function (value, elem) {
                var re = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                return re.test(value);
            },
            "Please enter a valid PAN"
        );
    </script>
@stop