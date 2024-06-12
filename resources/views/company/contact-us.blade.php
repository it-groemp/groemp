@extends("layouts.app")
@section("pageTitle","Contact Us")
@section("css")
    <style type="text/css">
        .container .email{
            font-size: 20px;
            font-weight: bold;
            color: #04bc64;
            background: none;
        }

        .email-link, .email-link:hover{
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .success{
            color: #FF914D;
            font-weight: bold;
        }

    </style>
@stop
@section("content")
    <div class="heading text-center py-4">
        <h1><i>CONTACT US</i></h1>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 col-12">  
                <p>
                    <span class="email">Registered Address:</span><br/>
                    <p class="address">
                        <h5>Groemp Services Private Limited</h5>
                        <p>Hiranandani<br/>Powai<br/>Mumbai-400076</p>
                        <p><a class="email-link" href="mailto:{{config('app.contact')}}?subject=Query">{{config('app.contact')}}</a></p>
                    </p>
                </p>
            </div>
            <div class="col-md-6 col-12 mt-4 mt-md-0">
                @if ($errors->any())
                    <div class="alert error mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @elseif(session()->has("success"))
                    <div class="success mb-4">{{session()->get("success")}}</div>
                    @php
                        Session::forget("success");
                    @endphp
                @endif
                <p class="email title pl-0">
                    In case of any query, please submit the same via the form below. Our Team will revert within 1 business day.
                </p>                
                <div class="form pt-0 p-4">
                    <form id="contact-form" method="post" action="{{route('submit-query')}}">
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
                            <label for="query">Query:</label>
                            <textarea class="form-control" rows="5" name="query" id="query" required></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-outline" value="Submit"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('js/validation.js')}}"></script>
    <script>
        $("#contact-form").validate({
            errorPlacement: function errorPlacement(error, element) {
                error.insertAfter(element);
            },
            rules:{
                name: {
                    alpha: true
                },
                email: {
                    email: true
                }
            },
            messages:{
                email: {
                    email: "Please enter a Valid Email Id"
                },
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });
    </script>
@stop