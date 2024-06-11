@extends('layouts.app')
@section('pageTitle','Our Partners')
@section('css')
@stop
@section("content")
<div class="container box mb-5">
    <h1 class="mb-3 text-center"><strong><i>OUR PARTNERS</i></strong></h1>
    <h4>Have a look at our partners...</h4>
    @php
        $prev="";
        $count = count($benefits);
    @endphp
    @for($i=0;$i<$count;$i++)
        @php
            $benefit = $benefits[$i];
        @endphp
        @if($prev!=$benefit->category_name)
            <div class="mt-4">
                <h4 class="mb-3"><b>{{$benefit->category_name}}:</b></h4>
                <div class="row">
        @endif
                    <div class="col-lg-4 col-md-6 col-12">
                        <img src="{{asset('images/benefits/'.$benefit->image_name)}}" class="p-2 mx-auto mb-4 border" alt="$benefit->name"/>
                    </div>
                    @php
                        $prev = $benefit->category_name;
                    @endphp
        @if(($i+1)!=$count && ($benefits[$i+1]->category_name != $benefit->category_name))
                </div>
            </div>
        @endif
    @endfor
                </div>
            </div>
@stop