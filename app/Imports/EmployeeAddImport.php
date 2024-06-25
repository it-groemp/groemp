<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeWelcomeMail;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use Illuminate\Support\Facades\Mail;
use App\Mail\ApproverEmployeeAddMail;

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
use Maatwebsite\Excel\Concerns\WithBatchInserts;

use \Validator;

class EmployeeAddImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithValidation, SkipsEmptyRows, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        $today = Carbon::now();
        $month = (str_pad($today->month, 2, "0", STR_PAD_LEFT).($today->format('y')));
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        $approval_pan = [];
        foreach($collection as $row){
            $row = $row->toArray();
            $company = array_key_exists("company_pan", $row) ? Str::upper($row["company_pan"]) : "";
            if($company!="" && (in_array($company, $company_list)==true || $role=="Admin")){
                $employee = new Employee();
                $employee->pan_number = Str::upper($row["employee_pan"]);
                $employee->employee_code = $row["employee_id"];
                $employee->name = $row["employee_name"];
                $employee->mobile = $row["employee_mobile"];
                $employee->email = $row["employee_email"];
                $employee->designation = $row["employee_designation"];
                $employee->company = Str::upper($company);
                $employee->marital_status = "Single";
                $employee->num_of_kids = 0;
                $employee->created_at = $today->toDateTimeString();
                $employee->created_by = $admin->email;
                $employee->updated_at = $today->toDateTimeString();
                $employee->updated_by = $admin->email;
                $employee->save();

                $employee_mail = new EmployeeWelcomeMail();
                $employee_mail->pan_number = Str::upper($row["employee_pan"]);
                $employee_mail->save();

                Log::info("EmployeeAddImport: ".$employee);
                
                $benefit_amount = $row["benefit_amount"];

                if($benefit_amount!=null || $benefit_amount!=""){
                    $employee_benefit = EmployeeBenefit::where("pan_number",Str::upper($row["employee_pan"]))->where("month",$month)->first();
                    if($employee_benefit!=null){
                        $employee_benefit->current_benefit = $benefit_amount;
                        $employee_benefit->updated_at = $today->toDateTimeString();
                        $employee_benefit->updated_by = $admin->email;
                        $employee_benefit->update();
                        Log::info("AddEmployeeImport: Update employee benefit:".$employee_benefit);
                    }
                    else{
                        $employee_benefit = new EmployeeBenefit();
                        $employee_benefit->pan_number = $row["employee_pan"];
                        $employee_benefit->company = Str::upper($company);
                        $employee_benefit->current_benefit = $benefit_amount;
                        $employee_benefit->month = $month;
                        $employee_benefit->created_at = $today->toDateTimeString();
                        $employee_benefit->created_by = $admin->email;
                        $employee_benefit->updated_at = $today->toDateTimeString();                            
                        $employee_benefit->updated_by = $admin->email;
                        $employee_benefit->save();
                        Log::info("AddEmployeeImport: Add employee benefit:".$employee_benefit);
                    }
                }
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
                $workflow_approval->created_by = $admin->email;
                $workflow_approval->save();
                $link=config("app.url")."/approve-employee-add-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverEmployeeAddMail($link));
                Log::info("approveEmployeeAddDetails(): Mail sent for approving Employee Addition to ".$workflow->approver1);
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
            "*.employee_designation" => ["required"],
            "*.benefit_amount" =>["required","numeric"]
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
            "employee_designation.required" => "Employee designation is required",
            "benefit_amount.required" => "Benefit amount is required",
            "benefit_amount.numeric" => "Benefit amount can have only numbers"
        ];
    }

    public function batchSize(): int
    {
        return 25;
    }
}
