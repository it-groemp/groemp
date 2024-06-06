@extends('admin.layouts.app')
@section('pageTitle','Add Company Benefit')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section("content")
    <div class="p-2 my-5 container">
        <h1 class="mb-3 text-center section-title">Add Company Benefits</h1>
        <form id="add-company-benefit-form" method="post" action="{{route('save-company-benefit')}}">
        {{ csrf_field() }}    
        <div class="row">
                @php
                    $prev="";
                    $count = count($benefits);
                @endphp
                @for($i=0;$i<$count;$i++)
                    @php
                        $benefit = $benefits[$i];
                        $category = strtolower(preg_replace('/\s+/', '', $benefit->category_name));
                        $benefit_name = strtolower(preg_replace('/\s+/', '', $benefit->name));
                    @endphp
                    @if($prev!=$benefit->category_name)
                        <div class="col-lg-4 col-md-6 col-12 mt-3">
                            <h5>
                                <div class="form-check">
                                    <input class="form-check-input category" type="checkbox" value="" id="{{$category}}">
                                    <label class="form-check-label" for="{{$category}}">
                                        {{$benefit->category_name}}
                                    </label>
                                </div>
                            </h5>
                            <ul>
                    @endif                
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input benefit {{$category}}" type="checkbox" name="benefit[]" value="{{$benefit->id}}" id="{{$benefit_name}}">
                                        <label class="form-check-label" for="{{$benefit_name}}">
                                            {{$benefit->name}}
                                        </label>
                                    </div>
                                </li>
                    @php
                        $prev = $benefit->category_name;
                    @endphp
                    @if(($i+1)!=$count && ($benefits[$i+1]->category_name != $benefit->category_name))
                            </ul>
                        </div>
                    @endif
                @endfor
                    </ul>
                </div>           
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Save Benefits"/>
            </div>
        </form>
    </div>
@stop
@section("js")
    <script>
        $(".category").change(function(){
            $id = $(this).attr("id");
            if($(this).is(":checked")){
                $("."+$id).prop("checked",true);
            }
            else{
                $("."+$id).prop("checked",false);
            }
        });

        $("#add-company-benefit-form").submit(function(e){
            if($(".benefit").is(':checked')==false){
                alert("please select atleast one benefit");
                e.preventDefault();
            }
        });
    </script>
@stop