@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Employee Details</h2>
        <div class="text-right my-5 pr-3 align-right">
			<a class="btn btn-outline" href="{{asset('files/Employee_Data.xlsx')}}">Download Employee Data Sheet</a>
			<button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadDataModalAdd">
                Upload Employee Data To Add
            </button>
            <button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadDataModalEdit">
                Upload Employee Data To Edit
            </button>
		</div>
        @if(count($employees)>0)
            @if($approval_status!=null)
                <ul class="error">
                    @foreach($approval_status as $as)
                        <li>
                            Employees approval for {{$as->company_name}} is pending for approval with {{$as->approver_email}}
                        </li>
                    @endforeach
                </ul>
            @endif
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Pan Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Email</th>
                    <th scope="col">Designation</th>
                    <th scope="col">View Details</th>
                </tr>
                @foreach($employees as $employee)
                    @php
                        $number=$loop->index+1;
                        $id = $employee->id;
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td id="{{'pan'.$id}}">{{$employee->pan_number}}</td>
                        <td id="{{'name'.$id}}">{{$employee->name}}</td>
                        <td id="{{'mobile'.$id}}">{{$employee->mobile}}</td>
                        <td id="{{'email'.$id}}">{{$employee->email}}</td>
                        <td id="{{'designation'.$id}}">{{$employee->designation}}</td>
                        <td>
                            <!-- <a class="btn btn-outline" id="{{'view'.$id}}" href="">
                                View
                            </a> -->
                            <button class="btn btn-outline edit" id="{{'edit'.$id}}">
                                Edit
                            </button>
                            <a class="btn btn-delete freeze" id="{{'freeze'.$id}}">
                                Freeze Account
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDataModalLabel">Update Employee Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-form" method="post" action="">
                        {{ csrf_field() }}
                        <div class="form-group mt-3">
                            <label for="pan"> PAN:</label>
                            <input type="text" class="form-control" name="pan" id="pan" maxlength=10 required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" maxlength=50 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="mobile">Mobile:</label>
                            <input type="tel" class="form-control" name="mobile" id="mobile" pattern="[6-9]{1}[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" minlength=10 maxlength=10 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" maxlength=100 required>
                        </div>
                        <div class="form-group">
                            <label for="name">Designation:</label>
                            <input type="text" class="form-control" name="designation" id="designation" maxlength=50 required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Benefit Amount:</label>
                            <input type="text" class="form-control" name="amount" id="amount" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="6" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="update" value="Update Employee Data"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="freezeModal" tabindex="-1" aria-labelledby="freezeModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="freezeModalLabel">Freeze Employee</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="text"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
					<a role="button" class="btn btn-outline" id="delete">Freeze Employee</a>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="uploadDataModalAdd" tabindex="-1" aria-labelledby="uploadDataModalAddLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadDataModalAddLabel">Upload Employee Data Excel To Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <form id="upload-form-add" method="post" action="{{route('save-employee-details')}}" enctype="multipart/form-data">
            			{{ csrf_field() }}
						<div class="form-group mt-3">
                            <input type="file" id="uploadFileAdd" name="uploadFileAdd" accept=".xlsx" required/>
                        </div>
					</form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="upload-btn-add" value="Upload Data"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadDataModalEdit" tabindex="-1" aria-labelledby="uploadDataModalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadDataModalEditLabel">Upload Employee Data Excel To Edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <form id="upload-form-edit" method="post" action="{{route('update-employee-details-bulk')}}" enctype="multipart/form-data">
            			{{ csrf_field() }}
						<div class="form-group mt-3">
                            <input type="file" id="uploadFileEdit" name="uploadFileEdit" accept=".xlsx" required/>
                        </div>
					</form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="upload-btn-edit" value="Upload Data"/>
                </div>
            </div>
        </div>
    </div>
@stop   
@section("js")
    <script>
        $("#upload-btn-add").click(function(){
            if($("#uploadFileAdd").val()==""){
                $(".error").html("Please upload the file");
            }
            else{
                $("#upload-form-add").submit();
            }
        });

        $("#upload-btn-edit").click(function(){
            if($("#uploadFileEdit").val()==""){
                $(".error").html("Please upload the file");
            }
            else{
                $("#upload-form-edit").submit();
            }
        });

        $(".edit").click(function(){
            $id=$(this).attr("id").substring(4,5);
            $("#pan").prop("value",$("#pan"+$id).html());
            $("#name").prop("value",$("#name"+$id).html());
            $("#mobile").prop("value",$("#mobile"+$id).html());
            $("#email").prop("value",$("#email"+$id).html());
            $("#designation").prop("value",$("#designation"+$id).html());
            $("#amount").prop("value",$("#amount"+$id).html());
            $action="/update-employee-details/"+$id;
            $("#update-form").attr("action",$action);
            $("#editDataModal").modal("show");
        });

        $("#upload-form").validate({
            rules:{
                pan: {
                    checkPan: true
                },
                name: {
                    alpha: true
                },
                mobile: {
                    checkPassword: true
                },
                email: {
                    email: true
                }
            },
            messages:{
                email: {
                    email: "Please enter a Valid Email Id"
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $.validator.addMethod("alpha", function (value, elem) {
                var re = /^[a-zA-Z .]+$/;
                return re.test(value);
            },
            "Only Capital, Small Letters, Spaces and Dot Allowed"
        );

        $.validator.addMethod("checkMobile", function (value, elem) {
                var re = /[6-9]{1}[0-9]{9}/;
                return re.test(value);
            },
            "Please enter a valid mobile number"
        );

        $.validator.addMethod("checkPan", function (value, elem) {
                var re = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                return re.test(value);
            },
            "Please enter a valid PAN"
        );

        $("#update").click(function(e){
			$("#update-form").submit();
		});

        $(".freeze").click(function(){
            $row = $(this).attr("id").substring(6,7);
            $name = $("#name"+$row).html();
            $text="Do you wish to freeze the employee "+$name+"?";
			$(".text").html($text);
			$action="/freeze-employee/"+$row;
        	$("#delete").attr("href",$action);
            $("#freezeModal").modal("show");
        });
    </script>
@stop