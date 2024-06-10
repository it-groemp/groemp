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
                    $employee->employee_code = $row["employee_id"];
                }
                if($row["employee_name"]!="NA"){
                    $employee->name = $row["employee_name"];
                }
                if($row["employee_mobile"]!="NA"){
                    $employee->name = $row["employee_mobile"];
                }
                if($row["employee_email"]!="NA"){
                    $employee->email = $row["employee_email"];
                }
                if($row["employee_designation"]!="NA"){
                    $employee->designation = $row["employee_designation"];
                }
                $employee->updated_at = Carbon::now()->toDateTimeString();
                $employee->updated_by = $admin->email;
                $employee->update();
                Log::info("EmployeeUpdateImport: ".$employee);
                array_push($approval_pan,Str::upper($company));   
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
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-edit-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverEmployeeEditMail($link));
                Log::info("EmployeeUpdateImport: Mail sent for approving Employee Updation to ".$workflow->approver2);
            }
        }
    }
}
