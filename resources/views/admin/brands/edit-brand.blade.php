@extends('admin.layouts.app')
@section('pageTitle','Edit Brand')
@section("css")
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Edit Brand</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="edit-brand" method="post" action="{{route('update-brand')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if($error->any())
                <div class="alert error mt-3">
                    <ul>
                        @foreach ($error->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" maxlength="50" value="{{$brand->name}}" required>
            </div>
            <div class="form-group mt-3">
                <label for="benefit" class="form-label">Benefit Name:</label>
                <select class="form-control" name="benefit" id="benefit" required>
                    @foreach($benefits as $benefit)
                        @if($brand->name == $benefit)
                            <option value="{{$benefit}}" selected>{{$benefit}}</option>
                        @else
                            <option value="{{$benefit}}">{{$benefit}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="photo" class="form-label">Brand Photo:</label>
                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept=".jpg, .jpeg, .png">
            </div>
            <div>
                <input type="hidden" name="id" value="{{$brand->id}}"/>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-outline" value="Update Brand"/>
            </div>
        </form>
</div>
@stop