@extends('admin.layouts.app')
@section('pageTitle','Admin Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-3">Admin Details</h2>
        @if(count($admins)>0)
            <table class="table">
                <tr>
                    <th scope="col">Sr. No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company</th>
                    <th scope="col">Role</th>
                </tr>
                @foreach($admins as $admin)
                    @php
                        $number=$loop->index+1;
                        $id = $admin->id;
                    @endphp
                    <tr>
                        <td>{{$number}}</td>
                        <td id="{{'name'.$id}}">{{$admin->name}}</td>
                        <td id="{{'mobile'.$id}}">{{$admin->mobile}}</td>
                        <td id="{{'email'.$id}}">{{$admin->email}}</td>
                        <td id="{{'company'.$id}}">{{$admin->company}}</td>
                        <td id="{{'role'.$id}}">{{$admin->role}}</td>
                    </tr>
                @endforeach
            </table>
        @endif
        <div class="text-right my-5 pr-3 align-right">
        <a class="btn btn-outline" href="{{route('add-admin')}}">Add Admin</a>           
		</div>
    </div>    
@stop
@section("js")
    <script link="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script link="{{asset('js/validation-admin.js')}}"></script>
@stop