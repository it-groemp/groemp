<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use App\Mail\ApproverCostCenterMail;

class ApprovalController extends Controller
{
    public function approveCCDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = Str::random(20);
                $workflow_approval->save();
                $token = Str::random(20);
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverCostCenterMail($link));
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $$workflow->$company;
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = Str::random(20);
                $workflow_approval->save();
                $token = Str::random(20);
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverCostCenterMail($link));
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.cost-center");
    }
}
