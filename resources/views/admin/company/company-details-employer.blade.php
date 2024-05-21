@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Company Details</h2>
        @if($group_company==null)   

        @else
            <div>
                <h4><b>Company Name:</b> {{$group_company->name}}</h4>
                <h4 class="pt-2">Contact Person Details:</h4>
                <h5><b>Name: </b>{{$admin->name}}
                <h5><b>Mobile: </b>{{$admin->mobile}}
                <h5><b>Email: </b>{{$admin->email}}

                <h4 class="pt-2">Address:</h4>
                <table class="no-left-margin">
                    <tr>
                        <th>State</th>
                        <th>City</th>
                        <th>Pincode</th>
                    </tr>
                    @foreach($group_address as $address)
                        <tr>
                            <td>{{$address->state}}</td>
                            <td>{{$address->city}}</td>
                            <td>{{$address->pincode}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="mt-4">
                <h3>Sub-Company Details:</h3>
                @foreach($sister_company as $sis)
                    <div class="mt-3">
                        <h4>Company: {{$loop->index+1}}</h4>
                        <h5><b>Company Name:</b> {{$sis->name}}</h5>
                        <h5><b>Mobile: </b>{{$sis->mobile}}
                        <h5><b>Email: </b>{{$sis->email}}
                        <h5 class="pt-2">Address:</h5>
                        @php
                            $addressSis = Arr::get($address_company,$sis->pan);
                            //dd($address_company);
                        @endphp
                        <table class="no-left-margin">
                            <tr>
                                <th>State</th>
                                <th>City</th>
                                <th>Pincode</th>
                            </tr>
                            @foreach($addressSis as $address_sis)
                                <tr>
                                    <td>{{$address_sis->state}}</td>
                                    <td>{{$address_sis->city}}</td>
                                    <td>{{$address_sis->pincode}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="text-right my-5 pr-3 align-right">
            <a class="btn btn-outline" href="{{route('register-company')}}">Register Company</a>
        </div>
    </div>
@stop