@extends("layouts.app") 
@section("pageTitle","Gadget Purchases")
@section("content")
    <div class="container mx-auto mb-5">
        <h1 class="mb-3 text-center"><strong><i>Gadget Purchases</i></strong></h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="{{route('fuel-solution')}}">Fuel Solutions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('uniformed-uniform')}}">Uniformed Uniform</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{route('gadget-purchases')}}">Gadget Purchases</a>
            </li>
        </ul>
        <div class="row mt-3">
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4" src="{{asset('images/benefits/samsung.png')}}"/><br/>
                    <textarea rows="5"></textarea><br/>
                    <button class="btn btn-colored mt-4">Save Details</button>
                </div>
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4" src="{{asset('images/benefits/hp.png')}}"/><br/>
                    <textarea rows="5"></textarea><br/>
                    <button class="btn btn-colored mt-4">Save Details</button>
                </div>
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4" src="{{asset('images/benefits/dell.png')}}"/><br/>
                    <textarea rows="5"></textarea><br/>
                    <button class="btn btn-colored mt-4">Save Details</button>
                </div>
            </div>
    </div>
@stop