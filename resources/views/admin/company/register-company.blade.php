@extends('admin.layouts.app')
@section('pageTitle','Register Company')
@section("css")
@stop
@section("content")
    <div class="p-2 my-5 form-container no-border mx-auto">
	    <h1 class="mb-3 text-center section-title">Register Company</h1>
        @if ($errors->any())
            <div class="alert error mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="text-right my-5 pr-3 align-right">
			<a class="btn btn-outline" href="{{asset('files/Register_Company.xlsx')}}">Download Registration Data Sheet</a>
			<button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#uploadDataModal">
                Upload Company Data
            </button>
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
                    
                    <form id="upload-form" method="post" action="{{route('save-company-details')}}" enctype="multipart/form-data">
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