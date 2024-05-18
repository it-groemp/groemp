@extends('admin.layouts.app')
@section('pageTitle','Brand Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section("content")    
    <div class="container my-5">
        <h2 class="text-center mb-3">Brand Details</h2>
        @if(count($brands)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Benefit Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Operation</th>
                </tr>
                @foreach($brands as $brand)
                    @php
                        $number=$loop->index+1;
                        $id = $brand->id;
                        $image_name = $brand->image_name
                    @endphp
                    <tr>
                        <td scope="row">{{$number}}</td>
                        <td id="{{'name'.$id}}">{{$brand->name}}</td>
                        <td id="{{'benefit'.$id}}">{{$brand->benefit_name}}</td>
                        <td>
                            <img src="{{asset('images/brands/'.$image_name)}}" width="100px" height="100px"/>
                        </td>
                        <td>
                            <a class="btn btn-outline" id="{{'edit'.$number}}" href="{{route('edit-brand',$id)}}">
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
        <a class="btn btn-outline align-right" href="{{route('add-brand')}}">Add brand</a>
    </div>
@stop
@section("js")
    <script>
        $(".btn-delete").click(function(){			
			$row=this.id.substring(6,7);
			$name=$("#name"+$row).html();
			$btn = confirm("Do you wish to delete "+$name+"?");
            if(!$btn){
                e.preventDefault();
            }
			else{
				$action="/delete-brand/"+$row;
				$("#delete").attr("href",$action);
				window.location = $action;
			}
		});
    </script>
@stop