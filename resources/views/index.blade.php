@extends('layouts.app')
@section('pageTitle','Home')
@section('css')
@stop
@section('content')
    <div class="container">
        <div class="home">
            <div class="row">
                <div class="col-md-7 col-12">
                    <img src="{{asset('images/financial_growth.jpg')}}" class="py-4 mx-auto growth-img" alt="Financial Growth"/> 
                </div>
                <div class="col-md-5 col-12 text-center">
                    <h1 class="my-5"><b>Grow Your Wealth<b></h1>
                    <h4><b>Unlock the power to save on your wealth and obtain great benefits with us.​</b></h4>
                    <a class="btn btn-colored my-5" href="">KNOW MORE...</a></p>
                </div>
            </div>
        </div>

        <div class="pb-5">
            <h1 class="mb-3 text-center section-title">What We Do</h1>
            <ul class="what-we-do">
                <li class="mt-1"><i class="fa fa-solid fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;We provide strategies to our clients for helping them learn on how to become independent in markets.</li>
                <li class="mt-1"><i class="fa fa-solid fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;We provide intraday as well as positional trading strategies.</li>
                <li class="mt-1"><i class="fa fa-solid fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;We provide strategies to identify short term and long term investment opportunities in markets</li>
                <li class="mt-1"><i class="fa fa-solid fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;We provide strategies for buying as well as selling in index as well as stock options.</li>
		    </ul>
        </div>

        <div class="pb-5">
            <h1 class="mb-3 text-center section-title">Benefits You Can Avail With Us</h1>
            <div class="row">
                @foreach($categories as $category)
                    @php
                        $image = $category->image_name
                    @endphp
                    <div class="span3 col-lg-3 col-md-6 col-12 mt-4">
                        <div class="box border">
                            <img src="{{asset('images/categories/'.$image)}}" class="p-2 mx-auto category-img" alt="{{$category->name}}"/>
                            <h4 class="text-center"><b>{{$category->name}}</b></h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="about-us pb-5">
            <h1 class="mb-3 text-center section-title">About Us</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur quis erat quis orci faucibus sagittis. Maecenas tempor, risus sit amet semper lobortis, elit dolor sodales quam, eget aliquam sapien nisi vitae tortor. Vestibulum cursus libero ac imperdiet sollicitudin. Maecenas a tempus velit, eget scelerisque tellus. Praesent nunc eros, consequat sit amet pharetra quis, fringilla at nunc. Nunc finibus eget nisi venenatis aliquet.
            </p>
        </div>

        <div class="why-us container pb-5">
            <div class="row">
                <div class="col-12">
                <h1 class="mb-3 text-center section-title">Why Us</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>Etiam in purus porttitor, vestibulum dolor vitae, laoreet justo</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>Phasellus at pharetra elit, id vehicula mauris</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 mt-4">
                    <div class="px-4 py-5 text-center reason">
                        <span>Vivamus aliquet in felis lobortis varius</span>
                    </div>
                </div>
            </div>
		    
	    </div>
    <div>
@stop