<?php

namespace App\Imports;

use App\Models\CostCenter;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Workflow;
use App\Models\WorkflowApproval;

use App\Mail\ApproverCostCenterMail;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CostCenterImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        $approval_pan = [];
        foreach($collection as $key => $row){
            if(in_array($row["company"], $company_list) || $role=="Admin"){
                $cost_center = new CostCenter();
                $cost_center->company = Str::upper($row["company"]);
                $cost_center->cc1 = $row["cc1"];
                $cost_center->cc2 = $row["cc2"] ?? "";
                $cost_center->cc3 = $row["cc3"] ?? "";
                $cost_center->cc4 = $row["cc4"] ?? "";
                $cost_center->cc5 = $row["cc5"] ?? "";
                $cost_center->cc6 = $row["cc6"] ?? "";
                $cost_center->cc7 = $row["cc7"] ?? "";
                $cost_center->cc8 = $row["cc8"] ?? "";
                $cost_center->cc9 = $row["cc9"] ?? "";
                $cost_center->cc10 = $row["cc10"] ?? "";
                $cost_center->created_at = Carbon::now()->toDateTimeString();
                $cost_center->created_by = $admin->email;
                $cost_center->updated_at = Carbon::now()->toDateTimeString();
                $cost_center->updated_by = $admin->email;
                $cost_center->save();
                array_push($approval_pan,Str::upper($row["company"]));
            }
        }
        foreach($approval_pan as $company){
            $workflow = Workflow::where("company",$company)->first();
            if($workflow!=null && $workflow->approver1!=null){
                $workflow_approval = new WorkflowApproval();
                $workflow_approval->company = $company;
                $workflow_approval->type="approver1";
                $workflow_approval->approver_email = $workflow->approver1;
                $workflow_approval->approval_for = "Cost Center";
                $workflow_approval->token = Str::random(20);
                $workflow_approval->save();
                $token = Str::random(20);
                $link=config("app.url")."/approve-cc-details/$token";
                Mail::to($workflow->approver1)->send(new ApproverCostCenterMail($link));
            }
        }
    }
}
