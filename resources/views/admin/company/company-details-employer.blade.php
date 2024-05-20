@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    
<a class="btn btn-outline" href="{{route('register-company')}}">Register Company</a>
@stop