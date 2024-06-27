@extends('admin.layouts.app')
@section('pageTitle','Category Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section("content")    
    <div class="container my-5">
        <h2 class="text-center mb-3">Category Details</h2>
        <a class="btn btn-outline align-right mb-3" href="{{route('add-category')}}">Add category</a>
        @if(count($categories)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Maximum Allowed Amount</th>
                    <th scope="col">Type</th>
                    <th scope="col">Values</th>
                    <th scope="col">Image</th>
                    <th scope="col">Operation</th>
                </tr>
                @foreach($categories as $category)
                    @php
                        $number=$loop->index+1;
                        $id = $category->id;
                        $image_name = $category->image_name
                    @endphp
                    <tr>
                        <td scope="row">{{$number}}</td>
                        <td id="{{'name'.$id}}">{{$category->name}}</td>
                        <td id="{{'desc'.$id}}" class="text-left">{!! $category->description !!}</td>
                        <td id="{{'amount'.$id}}">{{$category->maximum_amount}}</td>
                        <td id="{{'amount'.$id}}">{{$category->type}}</td>
                        <td id="{{'amount'.$id}}">{{$category->values=="null"?"" : $category->values}}</td>
                        <td>
                            <img src="{{asset('images/categories/'.$image_name)}}" width="100px" height="100px"/>
                        </td>
                        <td>
                            <a class="btn btn-outline mt-2" id="{{'edit'.$number}}" href="{{route('edit-category',$id)}}">
                                Edit
                            </a>
                            <button type="button" class="btn btn-delete mt-2" id="{{'delete'.$id}}">
								Delete
							</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@stop
@section("js")
    <script>
        $(".btn-delete").click(function(){	
            $length = $(this).attr("id").length;		
			$row=this.id.substring(6,$length);
			$name=$("#name"+$row).html();
			$btn = confirm("Do you wish to delete "+$name+"?");
            if(!$btn){
                e.preventDefault();
            }
			else{
				$action="/delete-category/"+$row;
				$("#delete").attr("href",$action);
				window.location = $action;
			}
		});
    </script>
@stop