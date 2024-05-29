@extends('admin.layouts.app')
@section('pageTitle','Add Benefit')
@section("css")
@stop
@section("content")
    <div class="p-2 my-5 form-container mx-auto">
	    <h1 class="mb-3 text-center section-title">Add Benefit</h1>
        <div class="form p-4 mx-auto" style="width: 70%">
        <form id="add-benefit" method="post" action="{{route('save-benefit')}}" enctype="multipart/form-data">
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
                <input type="text" class="form-control" name="name" id="name" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" class="form-control" name="amount" id="amount" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength="6" required>
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