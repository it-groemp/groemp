@extends('admin.layouts.app')
{{$function=="forgot"}}
@if($function=="forgot")
    @section('pageTitle', 'Forgot Password')    
@else
    @section('pageTitle', 'Set Password')
@endif
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container box">
        @if($function=="forgot")
            <h1 class="mb-3 text-center"><strong><i>Forgot Password</i></strong></h1>
        @else
            <h1 class="mb-3 text-center"><strong><i>Set Password</i></strong></h1>
        @endif
        
        @if(session("success"))
            <div class="success mb-3 p-3">{{session("success")}}</div>
        @elseif(session("error"))
            <div class="error mb-3 p-3">{{session("error")}}</div>
        @endif
        <div class="form p-4 mb-5">
        <form id="reset-password-form" method="post" action="{{route('send-password-link-admin',$function)}}">
                {{ csrf_field() }}
                <div class="form-group mt-3">
                    <label for="pan">Company PAN:</label>
                    <input type="text" class="form-control" name="pan" id="pan" maxlength=10 required>
                </div>
                <div class="form-group mt-3">
                    <input type="submit" class="btn btn-outline" value="Send Password Link"/>
                </div>
            </form>
        </div>
    </div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script link="{{asset('js/validation.js')}}"></script>
    <script>
        $("#reset-password-form").validate({
            rules:{
                pan: {
                    checkPan: true
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });
    </script>
@stop