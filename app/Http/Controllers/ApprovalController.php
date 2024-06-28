<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Employee;
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
use App\Mail\ApproverCompanyBenefitsMail;


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
            else{
                $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();

                    $employee_benefit = EmployeeBenfit::where("company",$employee->pan)->first();
                    $employee_benefit->verified = "Yes";
                    $employee_benefit->update();
                }
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
            else{
                $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();

                    $employee_benefit = EmployeeBenfit::where("company",$employee->pan)->first();
                    $employee_benefit->verified = "Yes";
                    $employee_benefit->update();
                }
            }
        }
        else{
            $workflow_approval->delete();
            $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
            foreach($employee_list as $employee){
                $employee->verified = "Yes";
                $employee->update();

                $employee_benefit = EmployeeBenfit::where("company",$employee->pan)->first();
                $employee_benefit->verified = "Yes";
                $employee_benefit->update();
            }
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
            else{
                $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
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
            else{
                $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
            }
        }
        else{
            $workflow_approval->delete();
            $employee_list = Employee::where("company",$workflow_approval->company)->where("verified","No")->get();
            foreach($employee_list as $employee){
                $employee->verified = "Yes";
                $employee->update();
            }
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
            else{
                $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
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
            else{
                $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
            }
        }
        else{
            $workflow_approval->delete();
            $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
            foreach($employee_list as $employee){
                $employee->verified = "Yes";
                $employee->update();
            }
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

    public function approveCompanyAddBenefits($token){
        $workflow_approval = WorkflowApproval::where("token",$token)->first();
        $workflow = Workflow::where("company",$workflow_approval->company)->first();
        if($workflow_approval->type == "approver1"){
            $workflow_approval->delete();
            if($workflow->approver2!=null){
                $workflow_approval->company = $workflow->company;
                $token = Str::random(20);
                $workflow_approval->type="approver2";
                $workflow_approval->approver_email = $workflow->approver2;
                $workflow_approval->approval_for = "Company Benefits";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-company-benefit-add-details/$token";
                Mail::to($workflow->approver2)->send(new ApproverCompanyBenefitsMail($link,"added"));
                Log::info("approveCompanyAddBenefits(): Mail sent for approving Employee Benefits Updation to ".$workflow->approver2);
            }
            else{                
                $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
            }
        }
        else if($workflow_approval->type == "approver2"){
            $workflow_approval->delete();
            if($workflow->approver3!=null){
                $workflow_approval->company = $workflow->$company;
                $token = Str::random(20);
                $workflow_approval->type="approver3";
                $workflow_approval->approver_email = $workflow->approver3;
                $workflow_approval->approval_for = "Company Benefits";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-company-benefit-add-details/$token";
                Mail::to($workflow->approver3)->send(new ApproverCompanyBenefitsMail($link, "added"));
                Log::info("approveCompanyAddBenefits(): Mail sent for approving Employee Benefits Updation to ".$workflow->approver3);
            }
            else{                
                $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
                foreach($employee_list as $employee){
                    $employee->verified = "Yes";
                    $employee->update();
                }
            }
        }
        else{
            $workflow_approval->delete();
            $employee_list = EmployeeBenefit::where("company",$workflow_approval->company)->where("verified","No")->get();
            foreach($employee_list as $employee){
                $employee->verified = "Yes";
                $employee->update();
            }
        }
        return view("admin.approvals.company-benefits");
    }
}
