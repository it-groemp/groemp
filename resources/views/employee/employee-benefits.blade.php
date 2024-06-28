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
    <div class="container mx-auto">
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
                    <div class="col-md-4 col-12 text-center">
                        <img class="mb-3" src="{{asset('images/benefits/'.$benefit->image_name)}}"/><br/>
                        <h4 id="{{'name'.$benefit->id}}">{{$benefit->name}}</h4>
                        <select id="{{'values'.$benefit->id}}">
                            @foreach($values as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                        <input type="numeric" placeholder="Enter Quantity" id="{{'quantity'.$benefit->id}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required/>
                        <br/>
                        <button class="btn btn-colored mt-4 save-dropdown" id="{{'save'.$benefit->id}}">Save Details</button>
                    </div>
                @endforeach
            </div>
            @elseif($type=="Free Number")
                @foreach($benefits_list as $benefit)
                    <div class="col-md-4 text-center">
                    <img class="mb-3" src="{{asset('images/benefits/'.$benefit->image_name)}}"/><br/>
                    <h4 id="{{'name'.$benefit->id}}">{{$benefit->name}}</h4>
                    <input type="numeric" class="mt-2" placeholder="Enter the amount" id="{{'amount'.$benefit->id}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required/><br/>
                    <button class="btn btn-colored mt-4 save-amount" id="{{'save'.$benefit->id}}">Save Amount</button>
                    </div>
                @endforeach
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
    </script>
@stop