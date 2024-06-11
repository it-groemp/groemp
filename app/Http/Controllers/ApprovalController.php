<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;

use App\Mail\ApproverCostCenterMail;
use App\Mail\ApproverEmployeeAddMail;
use App\Mail\ApproverEmployeeEditMail;
use App\Mail\ApproverEmployeeBenefitsAddMail;
use App\Mail\ApproverEmployeeBenefitsEditMail;


class ApprovalController extends Controller
{
    public function approveCCDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverCostCenterMail($link));
                Log::info("approveCCDetails(): Mail sent for approving Cost Centers to ".$workflow->approver2);
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverCostCenterMail($link));
                Log::info("approveCCDetails(): Mail sent for approving Cost Centers to ".$workflow->approver3);
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.cost-center");
    }

    public function approveEmployeeAddDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-add-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverEmployeeAddMail($link));
                Log::info("approveEmployeeAddDetails(): Mail sent for approving Employee Addition to ".$workflow->approver2);
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-add-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverEmployeeAddMail($link));
                Log::info("approveEmployeeAddDetails(): Mail sent for approving Employee Addition to ".$workflow->approver3);
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.employee");
    }

    public function approveEmployeeEditDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Employees Benefits";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-edit-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverEmployeeEditMail($link));
                Log::info("approveEmployeeEditDetails(): Mail sent for approving Employee Updation to ".$workflow->approver2);
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Employees Benefits";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-edit-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverEmployeeEditMail($link));
                Log::info("approveEmployeeEditDetails(): Mail sent for approving Employee Updation to ".$workflow->approver3);
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.employee");
    }

    public function approveEmployeeBenefitAddDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-add-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverEmployeeBenefitsAddMail($link));
                Log::info("approveEmployeeBenefitAddDetails(): Mail sent for approving Employee Benefits Addition to ".$workflow->approver2);
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-add-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverEmployeeBenefitsAddMail($link));
                Log::info("approveEmployeeBenefitAddDetails(): Mail sent for approving Employee Benefits Addition to ".$workflow->approver3);
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.employee");
    }

    public function approveEmployeeBenefitEditDetails($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-edit-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverEmployeeBenefitsEditMail($link));
                Log::info("approveEmployeeBenefitEditDetails(): Mail sent for approving Employee Benefits Updation to ".$workflow->approver2);
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-edit-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverEmployeeBenefitsEditMail($link));
                Log::info("approveEmployeeBenefitEditDetails(): Mail sent for approving Employee Benefits Updation to ".$workflow->approver3);
            }
        }
        else{
            $workflow_approval->delete();
        }
        return view("admin.approvals.employee");
    }
}
