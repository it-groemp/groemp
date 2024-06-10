<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeBenefitBackup;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use Illuminate\Support\Facades\Mail;
use App\Mail\ApproverEmployeeBenefitsEditMail;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class EmployeeBenefitUpdateImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $today = Carbon::now();
        $month = (str_pad($today->month, 2, "0", STR_PAD_LEFT).($today->format('y')));
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        $approval_pan = [];
        foreach ($collection as $row){
            $row = $row->toArray();
            $pan = array_key_exists("employee_pan", $row) ? Str::upper($row["employee_pan"]) : "";
            if($pan!=""){
                $employee = Employee::where("pan_number",$pan)->first();
                $company = $employee==null ? "" : $employee->company;
                if(in_array($company, $company_list) || $role=="Admin"){
                    $month = str_pad($row["benefit_month"], 2, "0", STR_PAD_LEFT);                        
                    $employee_benefit = EmployeeBenefit::where("pan_number",$pan)->where("month",$month)->first();            
                    $employee_benefit->current_benefit = $row["benefit_amount"];
                    $employee_benefit->updated_at = $today->toDateTimeString();
                    $employee_benefit->updated_by = $admin->email;
                    $employee_benefit->update();
                    Log::info("EmployeeBenefitUpdateImport: Employee Benefit: ".$employee_benefit);
                }
            }          
        }
        $approval_pan = array_unique($approval_pan);
        foreach($approval_pan as $company){
            $workflow = Workflow::where("company",$company)->first();
            if($workflow!=null && $workflow->approver1!=null){
                $workflow_approval = new WorkflowApproval();
                $token = Str::random(20);
                $workflow_approval->company = $company;
                $workflow_approval->type="approver1";
                $workflow_approval->approver_email = $workflow->approver1;
                $workflow_approval->approval_for = "Employees Benefit";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-edit-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverEmployeeBenefitsEditMail($link));
                Log::info("EmployeeBenefitUpdateImport: Mail sent for approving Employee Benefits Updation to ".$workflow->approver1);
            }
        }
    }
}
