<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use Illuminate\Support\Facades\Mail;
use App\Mail\ApproverEmployeeEditMail;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class EmployeeUpdateImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
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
            $company = array_key_exists("company_pan", $row) ? Str::upper($row["company_pan"]) : "";
            if($company!="" && (in_array($company, $company_list)==true || $role=="Admin")){
                $pan = Str::upper($row["employee_pan"]);
                $employee = Employee::where("employee_pan",$pan)->first();
                if($row["employee_id"]!="NA"){
                    $employee->employee_code = Str::upper($row["employee_id"]);
                }
                if($row["employee_name"]!="NA"){
                    $employee->name = $row["employee_name"];
                }
                if($row["employee_mobile"]!="NA"){
                    $employee->name = $row["employee_mobile"];
                }
                if($row["employee_email"]!="NA"){
                    $employee->email = Str::lower($row["employee_email"]);
                }
                if($row["employee_designation"]!="NA"){
                    $employee->designation = Str::upper($row["employee_designation"]);
                }
                if($row["approver_1_email_id"]!="NA"){
                    $employee->approver1 = $row["approver_1_email_id"]==null ? null : Str::lower($row["approver_1_email_id"]);
                }
                if($row["approver_2_email_id"]!="NA"){
                    $employee->approver2 = $row["approver_2_email_id"]==null ? null : Str::lower($row["approver_2_email_id"]);
                }
                if($row["approver_3_email_id"]!="NA"){
                    $employee->approver3 = $row["approver_3_email_id"]==null ? null : Str::lower($row["approver_3_email_id"]);
                }
                $employee->verified = "No";
                $employee->updated_by = $admin->email;
                $employee->update();
                Log::info("EmployeeUpdateImport: ".$employee);
                array_push($approval_pan,$company);   
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
                $workflow_approval->approval_for = "Employees";
                $workflow_approval->token = $token;
                $workflow_approval->created_by = $admin->email;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-edit-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverEmployeeEditMail($link));
                Log::info("EmployeeUpdateImport: Mail sent for approving Employee Updation to ".$workflow->approver2);
            }
        }
    }

    public function rules(): array
    {
        return [
            "*.company_pan" => ["required","regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/"],
            "*.employee_pan" => ["required","regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/","unique:employees,pan_number"],
            "*.employee_id" => ["required"],
            "*.employee_name" => ["required","regex:/^[a-zA-Z .]+$/"],
            "*.employee_mobile" => ["required","regex:/[6-9]{1}[0-9]{9}/","unique:employees,mobile"],
            "*.employee_email" => ["required","regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/","unique:employees,email"],
            "*.employee_designation" => ["required"]
        ];
    }

    public function customValidationMessages()
    {
        return [
            "company_pan.required" => "Company PAN is required",
            "company_pan.regex" => "Company PAN is invalid",
            "employee_pan.required" => "Employee PAN is required",
            "employee_pan.regex" => "Employee PAN is invalid",
            "employee_pan.unique" => "Employee PAN is already registered",
            "employee_id.required" => "Company Employee ID is required",
            "employee_name.required" => "Employee name is required",
            "employee_name.regex" => "Only Capital, Small Letters, Spaces and Dot Allowed for name",
            "employee_mobile.required" => "Employee mobile is required",
            "employee_mobile.regex" => "Employee mobile number is invalid",
            "employee_mobile.unique" => "Employee mobile is already registered",
            "employee_email.required" => "Employee email is required",
            "employee_email.regex" => "Employee email is invalid",
            "employee_email.unique" => "Employee email is already registered",
            "employee_designation.required" => "Employee designation is required"
        ];
    }
}
