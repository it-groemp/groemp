@extends('layouts.app')
@section('pageTitle','Our Brands')
@section('css')
@stop
@section("content")
<div class="container box mb-5">
    <h1 class="mb-3 text-center"><strong><i>OUR BRANDS</i></strong></h1>
    <h4>Have a look at our partners...</h4>
    @php
        $prev="";
        $count = count($brands);
    @endphp
    @for($i=0;$i<$count;$i++)
        @php
            $brand = $brands[$i];
        @endphp
        @if($prev!=$brand->benefit_name)
            <div class="mt-4">
                <h4><b>{{$brand->benefit_name}}:</b></h4>
                <div class="row">
        @endif
                    <div class="col-lg-3 col-md-6 col-12">
                        <img src="{{asset('images/brands/'.$brand->image_name)}}" class="p-2 mx-auto mb-4 border" alt="$brand->name"/>
                    </div>
                    @php
                        $prev = $brand->benefit_name;
                    @endphp
        @if(($i+1)!=$count && ($brands[$i+1]->benefit_name != $brand->benefit_name))
                </div>
            </div>
        @endif
    @endfor
                </div>
            </div>
@stop