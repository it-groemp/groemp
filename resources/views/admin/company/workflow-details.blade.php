@extends('admin.layouts.app')
@section('pageTitle','Register Company')
@section("content")
    <div class="container my-5">
        <h2 class="text-center mb-3">Workflow Details</h2>
        @if(session("error"))
            <div class="error mb-3">{!!session("error")!!}</div>
        @endif
        <div class="text-right my-5 pr-3 align-right">
            <button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#addWorkflowModal">
                Add Workflow Details
            </button>
		</div>
        @if(count($workflow)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Company PAN</th>
                    <th scope="col">Approver 1</th>
                    <th scope="col">Approver 2</th>
                    <th scope="col">Approver 3</th>
                    <th scope="col">Groemp Approver</th>
                    <th scope="col">Operations</th>
                </tr>
                @foreach($workflow as $wf)
                    @php
                        $number=$loop->index+1;
                        $id = $wf->id;
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td id="{{'company'.$id}}">{{$wf->company}}</td>
                        <td id="{{'ap1'.$id}}">{{$wf->approver1}}</td>
                        <td id="{{'ap2'.$id}}">{{$wf->approver2}}</td>
                        <td id="{{'ap3'.$id}}">{{$wf->approver3}}</td>
                        <td id="{{'admin'.$id}}">{{$wf->admin}}</td>
                        <td>
                            <button class="btn btn-outline edit" id="{{'edit'.$id}}">
                                Edit
                            </button>
                        </td>
                @endforeach
            </table>
        @endif
    </div>
    <div class="modal fade" id="addWorkflowModal" tabindex="-1" aria-labelledby="addWorkflowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addWorkflowModalLabel">Add Workflow Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-workflow-form" method="post" action="{{route('save-workflow')}}">
                        {{ csrf_field() }}
                        <div class="form-group mt-3">
                            <label for="company"> Company PAN:</label>
                            <select class="form-select" name="company" id="company">
                                @foreach($company_list as $company)
                                    <option value="{{$company}}">{{$company}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver1">Approver 1:</label>
                            <input type="email" class="form-control" name="approver1" id="approver1" maxlength=100 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver2">Approver 2:</label>
                            <input type="email" class="form-control" name="approver2" id="approver2" maxlength=100>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver3">Approver 3:</label>
                            <input type="email" class="form-control" name="approver3" id="approver3" maxlength=100>
                        </div>
                        <div class="form-group mt-3">
                            <label for="admin">Admin</label>
                            <select class="form-select" name="admin" id="admin">
                                @foreach($admin_list as $admin)
                                    <option value="{{$admin}}">{{$admin}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="save" value="Save Details"/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editWorkflowModal" tabindex="-1" aria-labelledby="editWorkflowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editWorkflowModalLabel">Edit Workflow Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-workflow-form" method="post" action="">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="company-edit">Company PAN:</label>
                            <input type="text" class="form-control" name="company-edit" id="company-edit" maxlength=10 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver1-edit">Approver 1:</label>
                            <input type="email" class="form-control" name="approver1-edit" id="approver1-edit" maxlength=100 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver2-edit">Approver 2:</label>
                            <input type="email" class="form-control" name="approver2-edit" id="approver2-edit" maxlength=100>
                        </div>
                        <div class="form-group mt-3">
                            <label for="approver3-edit">Approver 3:</label>
                            <input type="email" class="form-control" name="approver3-edit" id="approver3-edit" maxlength=100>
                        </div>
                        <div class="form-group">
                            <label for="admin-edit">Admin:</label>
                            <input type="text" class="form-control" name="admin-edit" id="admin-edit" maxlength=50 required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="update" value="Update Details"/>
                </div>
            </div>
        </div>
    </div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script link="{{asset('js/validation.js')}}"></script>
    <script>
        $("#save").click(function(){
            $("#add-workflow-form").submit();
        });

        $("#add-workflow-form").validate({
            rules:{
                approver1: {
                    email: true
                },
                approver2: {
                    email: true
                },
                approver3: {
                    email: true
                },
            },
            messages:{
                approver1: {
                    email: "Please enter a valid Email Id for Approver 1"
                },
                approver2: {
                    email: "Please enter a valid Email Id for Approver 2"
                },
                approver3: {
                    email: "Please enter a valid Email Id for Approver 3"
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $(".edit").click(function(){
            $id=$(this).attr("id").substring(4,5);
            $("#company-edit").prop("value",$("#company"+$id).html());
            $("#approver1-edit").prop("value",$("#ap1"+$id).html());
            $("#approver2-edit").prop("value",$("#ap2"+$id).html());
            $("#approver3-edit").prop("value",$("#ap3"+$id).html());
            $("#admin-edit").prop("value",$("#admin"+$id).html());
            $action="/update-workflow/"+$id;
            $("#edit-workflow-form").attr("action",$action);
            $("#editWorkflowModal").modal("show");
        });     
        
        $("#edit-workflow-form").validate({
            rules:{
                approver1: {
                    email: true
                },
                approver2: {
                    email: true
                },
                approver3: {
                    email: true
                },
            },
            messages:{
                approver1: {
                    email: "Please enter a valid Email Id for Approver 1"
                },
                approver2: {
                    email: "Please enter a valid Email Id for Approver 2"
                },
                approver3: {
                    email: "Please enter a valid Email Id for Approver 3"
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $("#update").click(function(){
            $("#edit-workflow-form").submit();
        });
    </script>
@stop