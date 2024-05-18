@extends('layouts.app')
@section('pageTitle','Home')
@section("content")
    <form id="upload-form" method="post" action="{{route('save')}}" enctype="multipart/form-data">
        {{ csrf_field() }}
		<div class="form-group mt-3">
            <input type="file" id="uploadFile" name="uploadFile" accept=".xlsx" required/>
            <input type="submit" value="Upload"/>
        </div>
	</form>
@stop