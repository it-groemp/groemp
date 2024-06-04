@extends('admin.layouts.app')
@section('pageTitle','Edit Category')
@section("css")
@stop
@section("content")
<div class="p-2 my-5 form-container mx-auto">
    <h1 class="mb-3 text-center section-title">Edit Category</h1>
    <div class="form p-4 mx-auto" style="width: 70%">
        <form id="edit-category-form" method="post" action="{{route('update-category')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if(session()->has("errors"))
                <div class="alert error mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @php
                $values=array();
                $count=0;
                if($category->values!=null){
                    $values = json_decode($category->values);
                    $count = count($values);
                }
            @endphp
            <div class="form-group mb-3">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" maxlength="50" value="{{$category->name}}" required>
            </div>
            <label>Type of Voucher:</label>
            <div class="form-check">                
                @if($category->type=="Dropdown")
                    <input class="form-check-input" type="radio" name="type" id="dropdown" value="Dropdown" checked>
                    <label class="form-check-label" for="dropdown">
                        Dropdown
                    </label>      
                    <span class='fa fa-plus add mx-3' style="visibility: visible;"></span>
                    <span class='fa fa-minus' style="visibility: visible;"></span>
                @else
                    <input class="form-check-input" type="radio" name="type" id="dropdown" value="Dropdown">
                    <label class="form-check-label" for="dropdown">
                        Dropdown
                    </label>      
                    <span class='fa fa-plus add mx-3' style="visibility: hidden;"></span>
                    <span class='fa fa-minus' style="visibility: hidden;"></span>
                @endif
                <div class="form-group mt-3" id="values">
                    @foreach($values as $key => $value)
                        <div id="#category{{$key+1}}">
                            <label for="value{{$key+1}}">Value {{$key+1}}:</label>
                            <input type="number" class="form-control mb-2" name="value{{$key+1}}" id="value{{$key+1}}" value="{{$value}}"maxlength="6">    
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-check">
                @if($category->type=="Free Number")
                    <input class="form-check-input" type="radio" name="type" id="number-field" value="Free Number" checked>
                @else
                    <input class="form-check-input" type="radio" name="type" id="number-field" value="Free Number">
                @endif    
                <label class="form-check-label" for="number-field">
                    Number Field
                </label>
            </div>
            <div class="form-check">
                @if($category->type=="Free Number")
                    <input class="form-check-input" type="radio" name="type" id="free-text" value="Text" checked>
                @else
                    <input class="form-check-input" type="radio" name="type" id="free-text" value="Text">
                @endif                    
                <label class="form-check-label" for="free-text">
                    Free Text
                </label>
            </div>
            <div class="form-group mt-3">
                <label for="amount">Maximum Allowed Amount:</label>
                <input type="text" class="form-control" name="amount" id="amount" value="{{$category->amount}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="6">
            </div>
            <div class="form-group mt-3">
                <label for="photo" class="form-label">Category Photo:</label>
                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept=".jpg, .jpeg, .png">
            </div>
            <div>
                <input type="hidden" name="id" value="{{$category->id}}"/>
                <input type="hidden" name="count" id="count" value="{{$count}}"/>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Update Category"/>
            </div>
        </form>
    </div>
</div>
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Add Category</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="add-category-form" method="post" action="{{route('update-category')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if(session()->has("errors"))
                <div class="alert error mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" maxlength="50" required>
            </div>
            <div class="form-group mt-3">
                <label>Type of Voucher:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="dropdown" value="Dropdown">
                    <label class="form-check-label" for="dropdown">
                        Dropdown
                    </label>      
                    <span class="fa fa-plus add mx-3" style="visibility: hidden;"></span>
                    <span class="fa fa-minus" style="visibility: hidden;"></span>              
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="number-field" value="Free Number" checked>
                    <label class="form-check-label" for="number-field">
                        Number Field
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="free-text" value="Text">
                    <label class="form-check-label" for="free-text">
                        Free Text
                    </label>
                </div>
            </div>
            <div class="form-group mt-3" id="values" style="display: none;">
                
            </div>
            <div class="form-group mt-3">
                <label for="amount">Maximum Allowed Amount:</label>
                <input type="text" class="form-control" name="amount" id="amount" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="6">
            </div>
            <div class="form-group mt-3">
                <label for="photo" class="form-label">Category Photo:</label>
                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept=".jpg, .jpeg, .png" required>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Save Category"/>
            </div>
        </form>
</div>
@stop
@section("js")
    <script>
        $i=parseInt($("#count").val())+1;
        $(".form-check-input").click(function(){
            if($("#dropdown").is(":checked")){
                $(".fa-plus").css("visibility","visible");
                $(".fa-minus").css("visibility","visible");
                $("#values").css("display","block");
            }
            else{
                $(".fa-plus").css("visibility","hidden");
                $(".fa-minus").css("visibility","hidden");
                $("#values").css("display","none");
            }
        });

        $(".fa-plus").click(function(){
            $values = "<div id='#category"+$i+"'><label for='value"+$i+"'>Value "+$i+":</label>" + 
                        "<input type='number' class='form-control mb-2' name='value"+$i+"' id='value"+$i+"' maxlength='6'></div>";
            $("#values").append($values);
            $i = $("#values").children().length+1;
            $("#count").prop("value",$i-1);
            $("#values").css("display","block");
        });

        $(".fa-minus").click(function(){
            if($i>2){
                $("#values").children().last().remove();          
                $i = $("#values").children().length+1;
                $("#count").prop("value",$i-1);
            }
        });

        $("form").submit(function(e){
            $error=false;
            if($("#dropdown").is(":checked")){
                $count=$("#count").val();
                if($count==0){
                    alert("Please add one dropdown value");
                    $error=true;
                }
                else{
                    for($i=1;$i<=$count;$i++){
                        if($("#value"+$i).val()==""){
                            alert("Value "+$i+" is required.");
                            $error=true;
                        }
                    }
                }
            }
            if($error){
                e.preventDefault();
            }            
        });
    </script>
@stop