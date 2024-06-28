@extends("layouts.app") 
@section("pageTitle","Employee Benefits")
@section("css")
    <style>
        h4{
            font-weight: bold;
        }
    </style>
@stop
@section("content")
    @include("employee.category-list")
    <div class="container mx-auto mb-5">
        <a class="btn btn-outline align-right mb-3" href="{{route('display-cart')}}">View Cart</a>
        <h1 class="my-3 text-center"><strong><i>{{$category->name}}</i></strong></h1>
        <h4 class="my-3 text-right">Balance Benefit Amount: {{session("benefit_amount")}}</h4>
        @if(session("success"))
            <div class="success mb-3 p-3 text-center"><b>{{session("success")}}</b></div>
        @elseif(session("error"))
            <div class="error mb-3 p-3 text-center"><b>{{session("error")}}</b></div>
        @endif
        @php
            $type = $category->type;
        @endphp

        @if($type=="Dropdown")
            @php
                $values = json_decode($category->values);
            @endphp
            <div class="row">
                @foreach($benefits_list as $benefit)
                    @php
                        $id = $benefit->id;
                    @endphp
                    <div class="col-md-4 col-12 text-center">
                        <img class="mb-3" src="{{asset('images/benefits/'.$benefit->image_name)}}"/><br/>
                        <h4 id="{{'name'.$id}}">{{$benefit->name}}</h4>
                        <select id="{{'values'.$id}}">
                            @foreach($values as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                        <input type="numeric" placeholder="Enter Quantity" id="{{'quantity'.$id}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required/>
                        <br/>
                        <button class="btn btn-colored mt-4 save-dropdown" id="{{'save'.$id}}">Save Details</button>
                    </div>
                @endforeach
            </div>
            @elseif($type=="Free Number")
                @foreach($benefits_list as $benefit)
                    @php
                        $id = $benefit->id;
                    @endphp
                    <div class="col-md-4 text-center">
                        <img class="mb-3" src="{{asset('images/benefits/'.$benefit->image_name)}}"/><br/>
                        <h4 id="{{'name'.$id}}">{{$benefit->name}}</h4>
                        <input type="numeric" class="mt-2" placeholder="Enter the amount" id="{{'amount'.$id}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required/><br/>
                        <button class="btn btn-colored mt-4 save-amount" id="{{'save'.$id}}">Save Amount</button>
                    </div>
                @endforeach
            @elseif($type=="Text")
                <div class="row">
                    @foreach($benefits_list as $benefit)
                    @php
                        $id = $benefit->id;
                    @endphp
                        <div class="col-md-4 text-center">
                            <img class="mb-3" src="{{asset('images/benefits/'.$benefit->image_name)}}"/><br/>
                            <h4 id="{{'name'.$id}}">{{$benefit->name}}</h4>
                            <textarea style="width: 100%;" rows="5" id="{{'desc'.$id}}"></textarea><br/>
                            <button class="btn btn-colored mt-4 save save-text" id="{{'save'.$id}}">Save Details</button>
                        </div>
                    @endforeach
                </div>
            @else

            @endif
            <div class="form">
                <form id="add-to-cart-form" method="post" action="{{route('update-category')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="benefit_id" id="benefit_id"/>
                    <input type="hidden" name="category_id" id="category_id" value="{{$category->id}}"/>
                    <input type="hidden" name="name" id="name"/>
                    <input type="hidden" name="price" id="price"/>
                    <input type="hidden" name="quantity" id="quantity"/>
                    <textarea hidden name="description" id="description"></textarea>
                </form>
            </div>
    </div>
    
@stop
@section("js")
    <script>
        $(".save-dropdown").click(function(){
            $length = $(this).attr("id").length;
            $id=$(this).attr("id").substring(4,$length);
            $("#benefit_id").prop("value",$id);
            $("#name").prop("value",$("#name"+$id).html());
            $("#quantity").prop("value",$("#quantity"+$id).val());
            $("#price").prop("value",$('#values'+$id).val());
            $("#add-to-cart-form").attr("action","/save-cart-dropdown");
            $("#add-to-cart-form").submit();
        });

        $(".save-amount").click(function(e){
            $length = $(this).attr("id").length;
            $id=$(this).attr("id").substring(4,$length);
            $amount = $('#amount'+$id).val();
            if($amount%100!=0){
                alert("Please enter the amount in multiples of 100");
                e.preventDefault();
            }
            else{
                $("#benefit_id").prop("value",$id);
                $("#name").prop("value",$("#name"+$id).html());
                $("#quantity").prop("value",0);
                $("#price").prop("value",$amount);
                $("#add-to-cart-form").attr("action","/save-cart-number");
                $("#add-to-cart-form").submit();
            }            
        });

        $(".save-text").click(function(e){
            $length = $(this).attr("id").length;
            $id=$(this).attr("id").substring(4,$length);
            $desc = $("#desc"+$id).val();
            if($desc==""){
                alert("Description of the gadget required");
                e.preventDefault();
            }
            else{
                $("#benefit_id").prop("value",$id);
                $("#name").prop("value",$("#name"+$id).html());
                $("#quantity").prop("value",0);
                $("#price").prop("value",0);
                $("#description").val($desc);
                $("#add-to-cart-form").attr("action","/save-cart-text");
                $("#add-to-cart-form").submit();      
            }            
        });

    </script>
@stop