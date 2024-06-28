@extends('layouts.app')
@section('pageTitle','Cart')
@section("content")
    @include("employee.category-list")
    <div class="container">
        <h2 class="text-center my-3">Selected Benefit Details</h2>
        <h4 class="my-3 text-right"><b>Balance Benefit Amount: {{session("benefit_amount")}}<b></h4>
        @if(session("success"))
            <div class="success mb-3 p-3">{{session("success")}}</div>
        @else
            @if(count($cart)>0)
            <button class="btn btn-outline align-right mb-3" id="save-benefits" href="{{route('display-cart')}}">Save Benefits</button>
                <table class="table">
                    <tr>
                        <th scope="col">Sr. No.</th>
                        <th scope="col">Benefit Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Delete</th>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach($cart as $benefit)
                        @php
                            $number=$loop->index+1;
                            $id = $benefit->id;
                            $quantity =  $benefit->quantity==0 ? "" : $benefit->quantity;
                            $total_value = $benefit->quantity==0 ? $benefit->price : $benefit->quantity * $benefit->price;
                            $total = $total + $total_value;
                        @endphp
                        <tr>
                            <td scope="row">{{$number}}</td>
                            <td id="{{'name'.$id}}">{{$benefit->benefit_name}}</td>
                            <td>{{$benefit->description}}</td>
                            <td>{{$benefit->price}}</td>
                            <td>{{$quantity}}</td>
                            <td>{{$total_value}}</td>
                            <td>
                                <a class="btn btn-delete delete" id="{{'delete'.$id}}">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="text-bold">
                        <td colspan="5">Total</td>
                        <td colspan>{{$total}}</td>
                        <td colspan></td>
                    </tr>
                </table>
            @else
                <div class="success mb-3 p-3 text-center"><b>Your cart is empty</b></div>
            @endif
        @endif
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">Delete Benefit</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="text"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
					<a role="button" class="btn btn-outline" id="delete">Delete Benefit</a>
				</div>
			</div>
		</div>
	</div>
@stop
@section("js")
    <script>
        $(".delete").click(function(){
            $length = $(this).attr("id").length;
            $row = $(this).attr("id").substring(6,$length);
            $name = $("#name"+$row).html();
            $text="Do you wish to delete the benefit "+$name+"?";
			$(".text").html($text);
			$action="/delete-from-cart/"+$row;
        	$("#delete").attr("href",$action);
            $("#deleteModal").modal("show");
        });

        $("#save-benefits").click(function(){
            $text = "Do you want to save these benefits? Once saved, you cannnot edit or delete the same.";
            if(confirm($text)==true){
                window.location = "/save-benefits";
            }
        });
    </script>
@stop