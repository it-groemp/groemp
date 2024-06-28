@extends('admin.layouts.app')
@section('pageTitle','Company Benefit Details')
@section("css")
    <link href="{{asset('css/admin-home.css')}}" rel="stylesheet">
@stop
@section("content")
    <div class="container my-5">
        <h2 class="text-center mb-3">Benefit Details</h2>        

        @if(isset($approval_status))
            <ul class="error">
                @foreach($approval_status as $as)
                    <li>
                        Company benefits approval for {{$as->company}} is pending for approval with {{$as->approver_email}}
                    </li>
                @endforeach
            </ul>
        @else
            @if(session("role")=="Employer")
                <a class="btn btn-outline align-right mb-3" href="{{route('add-company-benefit')}}">Add Benefit</a>
            @endif

            @if(count($benefits)>0)
                <table class="table">
                    <tr>
                        <th scope="col">Sr. No.</th>
                        <th scope="col">Company PAN</th>
                        <th scope="col">Benefits List</th>
                        <th scope="col">Operations</th>
                    </tr>
                    @foreach($benefits as $benefit)
                        @php
                            $cbList = [];
                            $number=$loop->index+1;
                            $id = $benefit->id;
                            $company_benefits = json_decode($benefit->benefits);
                            foreach($company_benefits as $cb){
                                array_push($cbList,$benefits_list[$cb-1]->name);
                            }                        
                        @endphp
                        <tr>
                            <td>{{$number}}</td>
                            <td id="{{'company'.$id}}">{{$benefit->company}}</td>
                            <td id="{{'list'.$id}}">
                                <button class="btn btn-outline" onClick='viewList(<?php echo json_encode($cbList); ?>)'>View Selected Benefits</button>
                            </td>
                            <td>
                                <a class="btn btn-outline edit" id="{{'edit'.$id}}" href="{{route('edit-company-benefit',$id)}}">
                                    Edit
                                </a>
                            </td>
                    @endforeach
                </table>
            @endif
        @endif        
    </div>

    <div class="modal fade" id="companyBenefitsList" tabindex="-1" aria-labelledby="companyBenefitsListLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companyBenefitsListLabel">Company Benefits List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section("js")
    <script>
        function viewList(arr){
            $(".modal-body").empty();
            $text = "<div><ul>";
            for(var i=0;i<arr.length;i++){
                $text+="<li>"+arr[i]+"</li>";
            }
            $text+="</ul></div>";
            $(".modal-body").append($text);
            $("#companyBenefitsList").modal("show");
        }
    </script>
@stop