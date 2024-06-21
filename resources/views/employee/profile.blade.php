@extends("layouts.app") 
@section("pageTitle","Profile")
@section("css")
    <style>
        .accordion-body{
            overflow:hidden;
        }

        .accordion-button:not(.collapsed){
            font-weight: bold;
            color:#04bc64;
            background-color: #d9ead6;
        }

        .accordion-button:focus {
            border-color:#04bc64;
        }
    </style>
@stop
@section("content")
    <div class="container mx-auto">
        <h1 class="mb-3 text-center"><strong><i>PROFILE</i></strong></h1>
        @if(session()->has("errors"))
            <div class="alert error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form form-container mx-auto p-4 mb-5">
            <div class="mb-4 text-center">
                <img src="{{asset('images/employee-images/'.$employee->photo)}}" alt="{{$employee->name}}" width="100"/>
            </div>
            <div class="text-center">
                <a href="#changePhotoModal" id="change-photo" data-toggle="modal">Change Photo</a>
                @if(isset($photo))
                    <a href="#deletePhotoModal" id="delete-photo" data-toggle="modal">Remove Photo</a>
                @endif
            </div>

            <div class="accordion" id="profile">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="personal">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="true" aria-controls="collapsePersonal">
                            Personal Details
                        </button>
                    </h2>
                    <div id="employee_kids" hidden>{{$employee->num_of_kids}}</div>
                    <div id="collapsePersonal" class="accordion-collapse collapse show" aria-labelledby="personal" data-bs-parent="#profile">
                        <div class="accordion-body">
                            <p><b>Name: </b><span id="emp_name">{{$employee->name}}</span></p>
                            <p><b>Employee Code: </b>{{$employee->employee_code}}</p>
                            <p><b>PAN: </b>{{$employee->pan_number}}</p>
                            <p><b>Mobile: </b><span id="emp_mobile">{{$employee->mobile}}</span></p>
                            <p><b>Email: </b>{{$employee->email}}</p>
                            <p><b>Date of Birth: </b><span id="emp_dob">{{$employee->date_of_birth}}</span></p>
                            <p><b>Designation: </b>{{$employee->designation}}</p>
                            <p><b>Company: </b>{{$company}}</p>
                            <button id="personal_btn" class="btn btn-outline pr-3 align-right" data-bs-toggle="modal" data-bs-target="#editPersonalModal">
                                Edit Personal Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="spouse">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpouse" aria-expanded="false" aria-controls="collapseSpouse">
                            Marital Information
                        </button>
                    </h2>
                    <div id="collapseSpouse" class="accordion-collapse collapse" aria-labelledby="spouse" data-bs-parent="#profile">
                        <div class="accordion-body">
                            <p><b>Marital Status: </b><span id="emp_marital_status">{{$employee->marital_status}}</span>
                                @if($employee->marital_status=="Single")
                                    <span class="error">    (By default, it is Single. Please click on Edit and update it.)</span>
                                @endif
                            </p>
                            @if($employee->marital_status=="Married")
                                <p><b>Spouse Name: </b><span id="emp_spouse_name">{{$family[0]["name"]}}</span></p>
                                <p><b>Spouse Date Of Birth: </b><span id="emp_spouse_dob">{{$family[0]["date_of_birth"]}}</span></p>
                            @endif
                            <button id="marital_btn" class="btn btn-outline pr-3 align-right" data-bs-toggle="modal" data-bs-target="#editMaritalModal">
                                Edit Marital Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="kids">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKids" aria-expanded="false" aria-controls="collapseKids">                                Kids
                        </button>
                    </h2>
                    <div id="collapseKids" class="accordion-collapse collapse" aria-labelledby="kids" data-bs-parent="#profile">
                        <div class="accordion-body">
                            <div class="row">
                                @php
                                    $i=1;
                                @endphp
                                @foreach($family as $member)
                                    @if($member["relation"]=="Kid")
                                        <div class="col-md-4 col-12">
                                            <p><b>Name: </b><span id="{{'emp_kid'.$i.'_name'}}">{{$member["name"]}}</span></p>                                                <p><b>Date Of Birth: </b><span id="{{'emp_kid'.$i.'_dob'}}">{{$member["date_of_birth"]}}</span></p>
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach
                            </div>
                            <button id="kids_btn" class="btn btn-outline pr-3 align-right" data-bs-toggle="modal" data-bs-target="#editKidsModal">
                                Edit Kids Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>                         
        </div>
    </div>

    <div class="modal fade" id="editPersonalModal" tabindex="-1" aria-labelledby="editPersonalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPersonalModalLabel">Edit Workflow Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-personal-form" method="post" action="{{route('save-personal')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" name="name" id="name" maxlength=50 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="mobile">Mobile:</label>
                            <input type="tel" class="form-control" name="mobile" id="mobile" pattern="[6-9]{1}[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" minlength=10 maxlength=10 required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="dob">Date Of Birth:</label>
                            <input type="date" class="form-control" name="dob" id="dob" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="update-personal" value="Update Details"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editMaritalModal" tabindex="-1" aria-labelledby="editMaritalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editMaritalModalLabel">Marital Status Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-marital-form" method="post" action="{{route("save-marital")}}">
                        {{ csrf_field() }}
                        <div class="form-group mt-3">
                            <label for="marital_status" class="form-label">Marital Status:</label>
                            <select class="form-control" name="marital_status" id="marital_status" required>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widow">Widow</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>

                        <div id="spouse_details" class="mt-4" style="display:none;">
                            <p class="mb-1"><b>Spouse Details:</b></p>
                            <div class="form-group">
                                <label for="spouse_name">Name:</label>
                                <input type="text" class="form-control" name="spouse_name" id="spouse_name" maxlength=50 required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="spouse_dob">Date Of Birth:</label>
                                <input type="date" class="form-control" name="spouse_dob" id="spouse_dob" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="update-marital" value="Update Details"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editKidsModal" tabindex="-1" aria-labelledby="editKidsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editKidsModalLabel">Kids Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-kids-form" method="post" action="{{route("save-kids")}}">
                        {{ csrf_field() }}
                        <div class="form-group mt-3">
                            <label for="num_of_kids" class="form-label">Number of Kids:</label>
                            <select class="form-control" name="num_of_kids" id="num_of_kids" required>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                        <div id="kid1" class="mt-4" style="display:none;">
                            <p class="mb-1"><b>Kid1:</b></p>
                            <div class="form-group">
                                <label for="kid1_name">Name:</label>
                                <input type="text" class="form-control" name="kid1_name" id="kid1_name" maxlength=50 required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="kid1_dob">Date Of Birth:</label>
                                <input type="date" class="form-control" name="kid1_dob" id="kid1_dob" required>
                            </div>
                        </div>
                        <div id="kid2" class="mt-4" style="display:none;">
                            <p class="mb-1"><b>Kid2:</b></p>
                            <div class="form-group">
                                <label for="kid2_name">Name:</label>
                                <input type="text" class="form-control" name="kid2_name" id="kid2_name" maxlength=50 required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="kid2_dob">Date Of Birth:</label>
                                <input type="date" class="form-control" name="kid2_dob" id="kid2_dob" required>
                            </div>
                        </div>
                        <div id="kid3" class="mt-4" style="display:none;">
                            <p class="mb-1"><b>Kid3:</b></p>
                            <div class="form-group">
                                <label for="kid3_name">Name:</label>
                                <input type="text" class="form-control" name="kid3_name" id="kid3_name" maxlength=50 required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="kid3_dob">Date Of Birth:</label>
                                <input type="date" class="form-control" name="kid3_dob" id="kid3_dob" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="update-kids" value="Update Details"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePhotoModal" tabindex="-1" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhotoModal">Change Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form id="change-photo-form" method="post" action="{{route('change-photo')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group mt-3">
                                <label for="employee-photo">Upload Photo:</label>
                                <input type="file" class="form-control form-control-sm" name="employee-photo" id="employee-photo" accept="image/*" required/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-outline" id="change-photo-btn" value="Change Photo"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePhotoModal">Delete Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your photo
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-outline" id="delete-photo-btn" href="{{route('delete-photo')}}">Delete Photo</a>
                </div>
            </div>
        </div>
    </div>
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script>
        $("#change-photo").click(function(){
            $("#changePhotoModal").modal("show");
        });

        $("#change-photo-btn").click(function(){
            if($("#cust-photo").val()==""){
                $(".error").html("Please upload a photo");
            }
            else{
                $("#change-photo-form").submit();
            }
        });

        $("#delete-photo").click(function(){
            $("#deletePhotoModal").modal("show");
        });

        $("#personal_btn").click(function(){
            $("#name").prop("value",$("#emp_name").html());
            $("#mobile").prop("value",$("#emp_mobile").html());
            $("#dob").prop("value",$("#emp_dob").html());
            $("#editPersonalModal").modal("show");
        });

        $("#update-personal").click(function(){
			$("#edit-personal-form").submit();
		});

        $("#edit-personal-form").validate({
            rules:{
                name: {
                    alpha: true
                },
                mobile: {
                    checkMobile: true
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $("#marital_btn").click(function(){      
            $status = $("#emp_marital_status").html();
            $("#marital_status > option").each(function() {
                if($(this).val( )==$status){
                    $(this).prop("selected","selected");
                }
            });

            if($status=="Married"){
                $("#spouse_details").css("display","block");
                $("#spouse_name").prop("value",$("#emp_spouse_name").html());
                $("#spouse_dob").prop("value",$("#emp_spouse_dob").html());
            }

            $("#editMaritalModal").modal("show");
        });

        $("#marital_status").change(function(){
            if($(this).val( )=="Married"){
                $("#spouse_details").css("display","block");
            }
            else{
                $("#spouse_details").css("display","none");
            }
        });
        
        $("#update-marital").click(function(){
			$("#edit-marital-form").submit();
		});

        $("#edit-marital-form").validate({
            rules:{
                spouse_name: {
                    alpha: true
                }
            },
            submitHandler : function(form) {
                form.submit();
            }   
        });

        $("#kids_btn").click(function(){      
            $employee_kids = $("#employee_kids").html();
            console.log($employee_kids);
            $("#num_of_kids > option").each(function() {
                if($(this).val()==$employee_kids){
                    $(this).prop("selected","selected");
                }
            });

            $("#kid1").css("display","none");
            $("#kid2").css("display","none");
            $("#kid3").css("display","none");

            if($employee_kids>0){
                $("#kid1_name").prop("value",$("#emp_kid1_name").html());
                $("#kid1_dob").prop("value",$("#emp_kid1_dob").html());
                $("#kid1").css("display","block");

                if($employee_kids>1){
                    $("#kid2_name").prop("value",$("#emp_kid2_name").html());
                    $("#kid2_dob").prop("value",$("#emp_kid2_dob").html());
                    $("#kid2").css("display","block");

                    if($employee_kids==3){
                        $("#kid3_name").prop("value",$("#emp_kid3_name").html());
                        $("#kid3_dob").prop("value",$("#emp_kid3_dob").html());
                        $("#kid3").css("display","block");
                    }
                }
                
            }
            $("#editKidsModal").modal("show");
        });

        $("#num_of_kids").change(function(){
            $("#kid1").css("display","none");
            $("#kid2").css("display","none");
            $("#kid3").css("display","none");

            if($(this).val()>0){
                $("#kid1").css("display","block");

                if($(this).val()>1){
                    $("#kid2").css("display","block");

                    if($(this).val()>2){
                        $("#kid3").css("display","block");
                    }
                }
            }
        });

        $("#update-kids").click(function(){
			$("#edit-kids-form").submit();
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
    </script>
@stop