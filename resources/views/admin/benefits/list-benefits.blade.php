@extends('admin.layouts.app')
@section('pageTitle','Benefit Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section("content")    
    <div class="container my-5">
        <h2 class="text-center mb-3">Benefit Details</h2>
        <a class="btn btn-outline align-right mb-3" href="{{route('add-benefit')}}">Add Benefit</a>
        @if(count($benefits)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Operation</th>
                </tr>
                @foreach($benefits as $benefit)
                    @php
                        $number=$loop->index+1;
                        $id = $benefit->id;
                        $image_name = $benefit->image_name
                    @endphp
                    <tr>
                        <td scope="row">{{$number}}</td>
                        <td id="{{'name'.$id}}">{{$benefit->name}}</td>
                        <td id="{{'benefit'.$id}}">{{$benefit->category_name}}</td>
                        <td>
                            <img src="{{asset('images/benefits/'.$image_name)}}" width="150px" height="100px"/>
                        </td>
                        <td>
                            <a class="btn btn-outline mt-2" id="{{'edit'.$number}}" href="{{route('edit-benefit',$id)}}">
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
			$row=this.id.substring(6,7);
			$name=$("#name"+$row).html();
			$btn = confirm("Do you wish to delete "+$name+"?");
            if(!$btn){
                e.preventDefault();
            }
			else{
				$action="/delete-benefit/"+$row;
				$("#delete").attr("href",$action);
				window.location = $action;
			}
		});
    </script>
@stop