@extends('admin.layouts.app')
@section('pageTitle','Add Brand')
@section("css")
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Add Benefit</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="add-brand" method="post" action="{{route('save-benefit')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if(session()->has("errors"))
                <div class="alert error mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" maxlength="50" required>
            </div>
            <div class="form-group mt-3">
                <label for="category" class="form-label">Category Name:</label>
                <select class="form-control" name="category" id="category" required>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="photo" class="form-label">Benefit Photo:</label>
                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept=".jpg, .jpeg, .png" required>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Save Benefit"/>
            </div>
        </form>
</div>
@stop