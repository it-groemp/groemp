@extends('admin.layouts.app')
@section('pageTitle','Company Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Company Details</h2>
        <div class="text-right my-5 pr-3 align-right">
            <a class="btn btn-outline" href="{{route('register-company')}}">Register Company</a>
        </div>
        @if(count($companies)>0)
            <table class="table">
                <tr>
                    <th>PAN Number</th>
                    <th>Name</th>
                    <th>Group Company PAN Number</th>
                    <th>Mobile</th>
                    <th>Email</th>
                </tr>
                @foreach($companies as $company)
                    <tr>
                        <td>{{$company->pan}}</td>
                        <td>{{$company->name}}</td>
                        <td>{{$company->group_company_name}}</td>
                        <td>{{$company->mobile}}</td>
                        <td>{{$company->email}}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@stop