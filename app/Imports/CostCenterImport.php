<?php

namespace App\Imports;

use App\Models\CostCenter;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use \Exception;

use App\Mail\ApproverCostCenterMail;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;


class CostCenterImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithValidation, SkipsEmptyRows
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
        foreach($collection as $key => $row){
            $company = $row["company_pan_no"];
            $array = [];
            if(in_array($company, $company_list) || $role=="Admin"){
                $cost_center = new CostCenter();
                $cost_center->company = Str::upper($company);
                $cost_center->cc1 = $row["cc_name_1"];
                array_push($array,$row["cc_name_1"]);
                $cost_center->cc2 = $row["cc_name_2"] ?? "";
                $cost_center->cc3 = $row["cc_name_3"] ?? "";
                $cost_center->cc4 = $row["cc_name_4"] ?? "";
                $cost_center->cc5 = $row["cc_name_5"] ?? "";
                $cost_center->cc6 = $row["cc_name_6"] ?? "";
                $cost_center->cc7 = $row["cc_name_7"] ?? "";
                $cost_center->cc8 = $row["cc_name_8"] ?? "";
                $cost_center->cc9 = $row["cc_name_9"] ?? "";
                $cost_center->cc10 = $row["cc_name_10"] ?? "";
                $cost_center->created_at = Carbon::now()->toDateTimeString();
                $cost_center->created_by = $admin->email;
                $cost_center->updated_at = Carbon::now()->toDateTimeString();
                $cost_center->updated_by = $admin->email;
                $cost_center->save();
                Log::info("CostCenterImport: ".$cost_center." added by admin: ".$admin->email);
                array_push($approval_pan,Str::upper($company));
            }
        }
        foreach($approval_pan as $company){
            $workflow = Workflow::where("company",$company)->first();
            if($workflow!=null && $workflow->approver1!=null){
                $workflow_approval = new WorkflowApproval();
                $token = Str::random(20);
                $workflow_approval->company = $company;
                $workflow_approval->type="approver1";
                $workflow_approval->approver_email = $workflow->approver1;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = $token;
                $workflow_approval->save();
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverCostCenterMail($link));
                Log::info("CostCenterImport: Mail sent for approving Cost Centers to ".$workflow->approver2);
            }
        }
    }
    
    public function rules(): array
    {
        return [
            "*.company_pan" => ["regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/","unique:cost_centers,company"],
            "*.cc_name_1" => ["required"]
        ];
    }

    public function customValidationMessages()
    {
        return [
            "company_pan.regex" => "Company PAN is invalid",
            "cc_name_1.required" => "CC Name 1 name is required"
        ];
    }
}
