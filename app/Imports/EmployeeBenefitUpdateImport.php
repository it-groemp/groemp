<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeBenefitBackup;
use App\Models\Admin;
use App\Models\Company;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $today = Carbon::now();
        $month = (str_pad($today->month, 2, "0", STR_PAD_LEFT).($today->format('y')));
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        foreach ($collection as $row){
            if($row["pan"]!=null){
                $pan = $row["pan"];
                $employee = Employee::where("pan_number",$pan)->first();
                $company = $employee->company;
                if(in_array($company, $company_list) || $role=="Admin"){
                    $month = str_pad($row["month"], 2, "0", STR_PAD_LEFT);
                    $employee_benefit = EmployeeBenefit::where("pan_number",$pan)->where("month",$month)->first();            
                    $employee_benefit->current_benefit = $row["benefit_amount"];
                    $employee_benefit->updated_at = $today->toDateTimeString();
                    $employee_benefit->updated_by = $admin->email;
                    $employee_benefit->update();
                }
            }            
        }
    }
}