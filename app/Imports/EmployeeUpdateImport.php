<?php

namespace App\Imports;

use App\Models\Employee;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class EmployeeUpdateImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        foreach ($collection as $row){
            $pan = Str::upper($row["pan_number"]);
            $employee = Employee::where("pan_number",$pan)->first();
            if($row["employee_id"]!="NA"){
                $employee->employee_code = $row["employee_id"];
            }
            if($row["name"]!="NA"){
                $employee->name = $row["name"];
            }
            if($row["email"]!="NA"){
                $employee->email = $row["email"];
            }
            if($row["designation"]!="NA"){
                $employee->designation = $row["designation"];
            }
            if($row["company"]!="NA"){
                $employee->company = Str::upper($row["company"]);
            }
            $employee->updated_at = Carbon::now()->toDateTimeString();
            $employee->updated_by = $admin->email;
            $employee->update();
        }
    }
}