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
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Illuminate\Validation\ValidationException;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithValidation;
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
        $row_num=2;
        foreach($collection as $key => $row){
            $company = $row["company_pan_no"];
            if(in_array($company, $company_list) || $role=="Admin"){
                $cost_center = new CostCenter();
                $cost_center->company = Str::upper($company);
                $array = [];
                for($i=1; $i<=10; $i++){
                    if($row["cc_name_".$i]==""){
                        Arr::set($array, $i, Str::upper($row["cc_name_".$i]));
                    }
                    else if(in_array(Str::upper($row["cc_name_".$i]),$array)){
                        throw ValidationException::withMessages(["cc_name_".$i => "There was an error on row ".$row_num.". CC Name ".$i." value already exists."]);
                    }
                    else{
                        Arr::set($array, $i, Str::upper($row["cc_name_".$i]));
                    }
                }
                for($i=1; $i<=10; $i++){
                    $name="cc".$i;
                    $cost_center->$name = Arr::get($array,$i);
                }
                $cost_center->created_at = Carbon::now()->toDateTimeString();
                $cost_center->created_by = $admin->email;
                $cost_center->updated_at = Carbon::now()->toDateTimeString();
                $cost_center->updated_by = $admin->email;
                $cost_center->save();
                $row_num++;
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
