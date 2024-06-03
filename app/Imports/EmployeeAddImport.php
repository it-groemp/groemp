<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

use \Validator;

class EmployeeAddImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithBatchInserts
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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        foreach ($collection as $row){
            if(in_array($row["company"], $company_list) || $role=="Admin"){
                $employee = new Employee();
                $employee->pan_number = Str::upper($row["pan_number"]);
                $employee->employee_code = $row["employee_id"];
                $employee->name = $row["name"];
                $employee->mobile = $row["mobile"];
                $employee->email = $row["email"];
                $employee->designation = $row["designation"];
                $employee->company = Str::upper($row["company"]);
                $employee->created_at = $today->toDateTimeString();
                $employee->created_by = $admin->email;
                $employee->updated_at = $today->toDateTimeString();
                $employee->updated_by = $admin->email;
                $employee->save();

                $benefit_amount = $row["benefit_amount"];

                if($benefit_amount!=null || $benefit_amount!=""){
                    $employee_benefit = EmployeeBenefit::where("pan_number",Str::upper($row["pan_number"]))->where("month",$month)->first();
                    if($employee_benefit!=null){
                        $employee_benefit->current_benefit = $benefit_amount;
                        $employee_benefit->updated_at = $today->toDateTimeString();
                        $employee_benefit->updated_by = $admin->email;
                        $employee_benefit->update();
                    }
                    else{
                        $employee_benefit = new EmployeeBenefit();
                        $employee_benefit->pan_number = $row["pan_number"];
                        $employee_benefit->company = Str::upper($row["company"]);
                        $employee_benefit->current_benefit = intval($row["benefit_amount"]);
                        $employee_benefit->month = $month;
                        $employee_benefit->created_at = $today->toDateTimeString();
                        $employee_benefit->created_by = $admin->email;
                        $employee_benefit->updated_at = $today->toDateTimeString();
                        $employee_benefit->updated_by = $admin->email;
                        $employee_benefit->save();
                    }
                }                
            }
        }
    }

    public function batchSize(): int
    {
        return 25;
    }
}
