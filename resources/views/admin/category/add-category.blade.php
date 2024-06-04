@extends("admin.layouts.app")
@section("pageTitle","Add Category")
@section("css")
    <style>
        .fa-plus{
            color: #04bc64;
            font-size: 110%;
        }
        .fa-minus{
            color: red;
            font-size: 110%;
        }
    </style>
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Add Category</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="add-category-form" method="post" action="{{route('save-category')}}" enctype="multipart/form-data">
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
            <div>
                <input type="hidden" name="count" id="count"/>
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
        $i=1;
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
                if($count==""){
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