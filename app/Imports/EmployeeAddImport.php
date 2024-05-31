<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Admin;

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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
        foreach ($collection as $row){
            if(in_array($row["company"], $company_list)){
                $employee = new Employee();
                $employee->pan_number = Str::upper($row["pan_number"]);
                $employee->employee_code = $row["employee_id"];
                $employee->name = $row["name"];
                $employee->mobile = $row["mobile"];
                $employee->email = $row["email"];
                $employee->designation = $row["designation"];
                $employee->company = Str::upper($row["company"]);
                $employee->created_at = Carbon::now()->toDateTimeString();
                $employee->created_by = $admin->email;
                $employee->updated_at = Carbon::now()->toDateTimeString();
                $employee->updated_by = $admin->email;
                $employee->save();
            }
        }
    }

    public function batchSize(): int
    {
        return 25;
    }
}
