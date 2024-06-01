@extends('admin.layouts.app')
@section('pageTitle','Employee Benefit Details')
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Employee Benefit Details</h2>
        @if(count($employee_benefits)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Pan Number</th>
                    <th scope="col">Company</th>
                    <th scope="col">Current Benefit</th>
                    <th scope="col">Previous Benefit</th>
                    <th scope="col">Availed Benefit</th>
                </tr>
                @foreach($employee_benefits as $employee_benefit)
                    @php
                        $number=$loop->index+1;
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td>{{$employee_benefit->pan_number}}</td>
                        <td >{{$employee_benefit->company}}</td>
                        <td>{{$employee_benefit->current_benefit}}</td>
                        <td>{{$employee_benefit->previous_benefit}}</td>
                        <td>{{$employee_benefit->availed_benefit}}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <div class="text-right my-5 pr-3 align-right">
			<a class="btn btn-outline" href="{{asset('files/Benefit_Data.xlsx')}}">Download Employee Benefits Data Sheet</a>
			<button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadBenefitData">
                Upload Employee Benefits Data
            </button>
		</div>
    </div>

    <div class="modal fade" id="uploadBenefitData" tabindex="-1" aria-labelledby="uploadBenefitDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadBenefitDataLabel">Upload Employee Data Excel To Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <form id="upload-employee-benefits" method="post" action="{{route('upload-employee-benefits')}}" enctype="multipart/form-data">
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
                $("#upload-employee-benefits").submit();
            }
        });
    </script>
@stop