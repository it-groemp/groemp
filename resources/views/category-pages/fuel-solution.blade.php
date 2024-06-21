@extends("layouts.app") 
@section("pageTitle","Fuel Solutons")
@section("content")
    <div class="container mx-auto mb-5">
        <h1 class="mb-3 text-center"><strong><i>Fuel Solutions</i></strong></h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{route('fuel-solution')}}">Fuel Solutions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('uniformed-uniform')}}">Uniformed Uniform</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('gadget-purchases')}}">Gadget Purchases</a>
            </li>
        </ul>
        <div class="row mt-3">
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4    " src="{{asset('images/benefits/iocl.jpg')}}"/>
                    <input type="numeric" class="mt-2" placeholder="Enter the amount"/><br/>
                    <button class="btn btn-colored mt-4">Save Amount</button>
                </div>
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4" src="{{asset('images/benefits/bpcl.jpg')}}"/>
                    <input type="numeric" class="mt-2"  placeholder="Enter the amount"/><br/>
                    <button class="btn btn-colored mt-4">Save Amount</button>
                </div>
                <div class="col-md-4 text-center">
                    <img class="p-2 mx-auto mb-4" src="{{asset('images/benefits/hpcl.png')}}"/>
                    <input type="numeric" class="mt-2"  placeholder="Enter the amount"/><br/>
                    <button class="btn btn-colored mt-4">Save Amount</button>
                </div>
            </div>
    </div>