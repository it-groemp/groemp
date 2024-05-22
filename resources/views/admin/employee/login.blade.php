@extends('admin.layouts.app')
@section('pageTitle','Admin Login')
@section('css')
    <link href="{{asset('css/register.css')}}" rel="stylesheet">
@stop
@section("content")
<div class="container box mb-5">
    <h1 class="mb-3 text-center"><strong><i>LOGIN</i></strong></h1>
    <div class="form p-4">
        @if(session("otpModal")=="yes")
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script>
                $(function(){
                    $("#otpModal").modal("show");
                });
            </script>
        @elseif(session()->has("errors"))
            <div class="error mb-3">{!!session()->get("errors")!!}</div>
        @endif
        <form id="login-form" method="post" action="{{route('admin-send-otp')}}">
            {{ csrf_field() }}
            <div class="form-group mt-3">
                <label for="mobile">Mobile:</label>
                <input type="tel" class="form-control" name="mobile" id="mobile" pattern="[6-9]{1}[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" minlength=10 maxlength=10 required>
            </div>            
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-colored">Send OTP</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
				<h5 class="modal-title" id="otpModal"><b>Verify OTP</b></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form p-4">
                    @if(session()->get("error"))
                        <div class="error mb-3 p-3">{!!session()->get("error")!!}</div>
                    @elseif(session("successResend"))
                        <div class="success mb-3 p-3"></div>
                    @endif
					<form id="otp-form" method="post" action="{{route('admin-verify-otp')}}">
            			{{ csrf_field() }}
						<div class="form-group mt-3">
							<label for="name">Enter OTP:</label>
							<input type="text" class="form-control" name="otp" id="otp" maxlength="6" required>
                            <span class="error mobile"></span>
                        </div>
                        
					</form>
				</div>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<input type="submit" class="btn btn-outline" id="verify-otp" value="Verify OTP"/>
			</div>
        </div>
    </div>
</div>
@stop
@section("js")
    <script style="text/javascript">
        $("#verify-otp").click(function(){
            $(".mobile").html("");
            $otp=$("#otp").val()+"";
            if($otp.length<6){
                $(".mobile").html("Please enter 6 digits OTP.");
            }
            else{
                $("#otp-form").submit();
            }
        });
    </script>
@stop