@extends('admin.layouts.app')
@section('pageTitle','Add Company Benefit')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
    <style>
        .form-code{
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .form-label-code{
            font-size: 1rem;
        }
    </style>
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
                        $category_id = $benefit->category_id;
                        $benefit_name = strtolower(preg_replace('/\s+/', '', $benefit->name));
                    @endphp
                    @if($prev!=$benefit->category_name)
                        <div class="col-lg-4 col-md-6 col-12 mt-3">
                            <h5>
                                <div class="form-check">
                                    <input class="form-check-input category" type="checkbox" value="{{$category_id}}" id="{{$category}}">
                                    <label class="form-check-label" for="{{$category}}">
                                        {{$benefit->category_name}}
                                    </label>
                                    <br/>
                                    <label class="form-label-code mt-3" for="{{'gl-category'.$category_id}}">
                                        GL Code:
                                        <input class="form-code gl-category" type="text" value="" name="{{'gl-category'.$category_id}}" id="{{'gl-category'.$category_id}}">
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
                                        <label class="form-label-code" for="{{'gl-benefit'.$benefit->id}}">
                                            <b>    GL Code:</b>
                                            <input class="form-code {{'gl-category'.$category_id}}" type="text" value="" width="50%" name="{{'gl-benefit'.$benefit->id}}" id="{{'gl-benefit'.$benefit->id}}">
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
            else{
                $('input.benefit:checkbox:checked').each(function () {
                    $id = $(this).val();
                    if($("#gl-benefit"+$id).val()==""){
                        $class=$("#gl-benefit"+$id).attr("class").split(" ")[1];
                        $val = $("#"+$class).val();
                        if($val==""){
                            alert("Please enter the GL code for "+$(this).attr("id")+" or its Category");
                            e.preventDefault();
                        }
                        else{
                            $("#gl-benefit"+$id).val($val);
                        }                        
                    }
                });
            }
        });

        $(".gl-category").blur (function(){
            $id=$(this).attr("id");
            $("."+$id).val($(this).val());
        });
    </script>
@stop