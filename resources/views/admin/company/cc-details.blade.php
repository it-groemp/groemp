@extends('admin.layouts.app')
@section('pageTitle','Employee Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
    <style>
        #popupDialog {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 20px
                    rgba(0, 0, 0, 0.3);
                z-index: 1000;
                visibility: hidden;
            }

    </style>
@stop
@section("content")
    <div class="container my-5">
        <h2 class="text-center mb-3">Cost Center Details</h2>
       
        @if(count($ccDetails)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Company PAN</th>
                    <th scope="col">Company Name</th>
                    <th scope="col">Cost Center Details</th>
                </tr>
                @foreach($ccDetails as $cc)
                    @php
                        $ccList = [];
                        $number=$loop->index+1;
                        for($i=1;$i<=10;$i++){
                            $col = "cc".$i;
                            array_push($ccList,$cc->$col);
                        }
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td>{{$cc->company}}</td>
                        <td>{{$cc->name}}</td>
                        <td>
                            <button class="btn btn-outline" onClick='openPop(<?php echo json_encode($ccList); ?>)'>View Cost Centers</button>
                        </td>
                    </tr>
                @endforeach
        @endif
        @if(Session::get("role")=="Employer")
            <div class="text-right my-5 pr-3 align-right">
                <a class="btn btn-outline" href="{{asset('files/Cost_Center.xlsx')}}">Download Cost Center Data Sheet</a>
                <button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadDataModal">
                    Upload Cost Center Data
                </button>
            </div>
        @endif
    </div>

    <div class="modal fade" id="ccDataModal" tabindex="-1" aria-labelledby="ccDataModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="ccDataModalLabel">List of Data Centers</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" id="cost-centers">
                        <ul>
                            <li id="cc1"></li>
                            <li id="cc2"></li>
                            <li id="cc3"></li>
                            <li id="cc4"></li>
                            <li id="cc5"></li>
                            <li id="cc6"></li>
                            <li id="cc7"></li>
                            <li id="cc8"></li>
                            <li id="cc9"></li>
                            <li id="cc10"></li>
                        </ul>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

    <div class="modal fade" id="uploadDataModal" tabindex="-1" aria-labelledby="uploadDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadDataModalLabel">Upload Company Data Excel</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                    
                    <form id="upload-form" method="post" action="{{route('save-cc-details')}}" enctype="multipart/form-data">
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

        function openPop(arr){
            for(var i=0;i<10;i++){
                var li = document.getElementById('cc'+(i+1));
                li.textContent = "CC"+(i+1)+": "+arr[i];
            }
            $("#ccDataModal").modal("show");
        }
    </script>
@stop