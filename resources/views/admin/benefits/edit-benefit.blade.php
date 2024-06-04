@extends('admin.layouts.app')
@section('pageTitle','Edit Benefit')
@section("css")
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Edit Benefit</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="edit-benefit" method="post" action="{{route('update-benefit')}}" enctype="multipart/form-data">
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
                <input type="text" class="form-control" name="name" id="name" maxlength="50" value="{{$benefit->name}}" required>
            </div>
            <div class="form-group mt-3">
                <label for="category" class="form-label">Category Name:</label>
                <select class="form-control" name="category" id="category" required>
                    @foreach($categories as $category)
                        @if($benefit->category_id == $category->id)
                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                        @else
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="photo" class="form-label">Benefit Photo:</label>
                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept=".jpg, .jpeg, .png">
            </div>
            <div>
                <input type="hidden" name="id" value="{{$benefit->id}}"/>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Update Benefit"/>
            </div>
        </form>
</div>
@stop