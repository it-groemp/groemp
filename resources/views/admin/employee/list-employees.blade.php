@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Employee Details</h2>
        @if(count($employees)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Pan Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Email</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Benefit Amount</th>
                    <th scope="col">View Details</th>
                </tr>
                @foreach($employees as $employee)
                    @php
                        $number=$loop->index+1;
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td>{{$employee->pan_number}}</td>
                        <td>{{$employee->name}}</td>
                        <td>{{$employee->mobile}}</td>
                        <td>{{$employee->email}}</td>
                        <td>{{$employee->designation}}</td>
                        <td>{{$employee->benefit_amount}}</td>
                        <td>
                            <a class="btn btn-outline" id="{{'view'.$number}}" href="">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
        <div class="text-right my-5 pr-3 align-right">
			<a class="btn btn-outline" href="{{asset('files/Employee_Data.xlsx')}}">Download Employee Data Sheet</a>
			<button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadDataModal">
                Upload Employee Data
            </button>
		</div>
    </div>
    
    <div class="modal fade" id="uploadDataModal" tabindex="-1" aria-labelledby="uploadDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadDataModalLabel">Upload Employee Data Excel</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <form id="upload-form" method="post" action="{{route('save-employee-details')}}" enctype="multipart/form-data">
            			{{ csrf_field() }}
						<div class="form-group mt-3">
                            <input type="file" id="uploadFile" name="uploadFile" accept=".xlsx" required/>
                        </div>
					</form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="upload-btn" value="Upload Data"/>
                </div>
            </div>
        </div>
    </div>
@stop   
@section("js")
    <script>
        $("#upload-btn").click(function(){
            if($("#uploadFile").val()==""){
                $(".error").html("Please upload the file");
            }
            else{
                $("#upload-form").submit();
            }
        });
    </script>
@stop