@extends("layouts.app") 
@section("pageTitle","Profile")
@section("content")
    <div class="container mx-auto">
        <h1 class="mb-3 text-center"><strong><i>Current Benefits</i></strong></h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('fuel-solution')}}">Fuel Solutions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('uniformed-uniform')}}">Uniformed Uniform</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('transport-facility')}}">Transport Facility</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('car-lease')}}">Car Lease</a>
            </li>
        </ul>
    </div>
@stop