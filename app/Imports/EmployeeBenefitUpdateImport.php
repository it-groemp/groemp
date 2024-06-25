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
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EmployeeBenefitUpdateImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithValidation, SkipsEmptyRows
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
                $workflow_approval->created_by = $admin->email;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-benefit-edit-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverEmployeeBenefitsEditMail($link));
                Log::info("EmployeeBenefitUpdateImport: Mail sent for approving Employee Benefits Updation to ".$workflow->approver1);
            }
        }
    }

    public function rules(): array
    {
        return [
            "*.employee_pan" => ["required","regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/"],
            "*.benefit_month" => ["required","numeric","digits:4"],
            "*.benefit_amount" =>["required","numeric"]
        ];
    }

    public function customValidationMessages()
    {
        return [
            "employee_pan.required" => "Employee PAN is required",
            "employee_pan.regex" => "Employee PAN is invalid",
            "benefit_month.required" => "Benefit month is required",
            "benefit_month.numeric" => "Benefit month should contain only numbers",
            "benefit_month.digits" => "Benefit month should contain only 4 digits",
            "benefit_amount.required" => "Benefit amount is required",
            "benefit_amount.numeric" => "Benefit amount can have only numbers"
        ];
    }
}
