@extends("layouts.app") 
@section("pageTitle","Uniformed Uniform")
@section("content")
    <div class="container mx-auto mb-5">
        <h1 class="mb-3 text-center"><strong><i>Uniformed Uniform</i></strong></h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('fuel-solution')}}">Fuel Solutions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{route('uniformed-uniform')}}">Uniformed Uniform</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('gadget-purchases')}}">Gadget Purchases</a>
            </li>
        </ul>
        <div class="row mt-3">
            <div class="col-md-4 col-12 text-center">
                <img class="mb-3" src="{{asset('images/benefits/ajio.jpg')}}"/><br/>
                <select>
                    <option>100</option>
                    <option>500</option>
                    <option>1000</option>
                    <option>5000</option>
                </select>
                <input type="numeric" placeholder="Enter Quantity"/>
                <br/>
                <button class="btn btn-colored mt-4">Save Details</button>
            </div>
            <div class="col-md-4 col-12 text-center">
                <img class="" src="{{asset('images/benefits/lifestyle.png')}}"/><br/>
                <select>
                    <option>100</option>
                    <option>500</option>
                    <option>1000</option>
                    <option>5000</option>
                </select>
                <input type="numeric" placeholder="Enter Quantity"/>
                <br/>
                <button class="btn btn-colored mt-4">Save Details</button>
            </div>
            <div class="col-md-4 col-12 text-center">
                <img class="mb-3" src="{{asset('images/benefits/pantaloons.jpg')}}"/><br/>
                <select>
                    <option>100</option>
                    <option>500</option>
                    <option>1000</option>
                    <option>5000</option>
                </select>
                <input type="numeric" placeholder="Enter Quantity"/>
                <br/>
                <button class="btn btn-colored mt-4">Save Details</button>
            </div>
        </div>
    </div>
@stop