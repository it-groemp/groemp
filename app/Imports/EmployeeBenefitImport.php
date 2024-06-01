<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class EmployeeBenefitImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
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
        foreach ($collection as $row){
            $pan = $row["pan"];
            $employee = Employee::where("pan_number",$pan)->first();
            $company = $employee->company;
            if(in_array($company, $company_list) || $role=="Admin"){
                $employee_benefit = EmployeeBenefit::where("pan_number",$pan)->first();
                if($employee_benefit==null){
                    $employee_benefit = new EmployeeBenefit();
                    $employee_benefit->pan_number = $pan;
                    $employee_benefit->current_benefit = $row["benefit_amount"];
                    $employee_benefit->created_at = Carbon::now()->toDateTimeString();
                    $employee_benefit->created_by = $admin->email;
                    $employee_benefit->updated_at = Carbon::now()->toDateTimeString();
                    $employee_benefit->updated_by = $admin->email;
                    $employee_benefit->save();
                }
                else{
                    $employee_benefit->previous_benefit = $employee_benefit->current_benefit;
                    $employee_benefit->current_benefit = $row["benefit_amount"];
                    $employee_benefit->updated_at = Carbon::now()->toDateTimeString();
                    $employee_benefit->updated_by = $admin->email;
                    $employee_benefit->update();
                }
            }
        }
    }
}
