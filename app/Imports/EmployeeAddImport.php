<?php

namespace App\Imports;

use App\Models\Employee;

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
        foreach ($collection as $row){
            Employee::create([
                "pan_number" => Str::upper($row["pan_number"]),
                "employee_code" => $row["employee_id"],
                "name" => $row["name"],
                "mobile" => $row["mobile"],
                "email" => $row["email"],
                "designation" => $row["designation"],
                "company" => Str::upper($row["company"])
            ]);
        }
    }

    public function batchSize(): int
    {
        return 25;
    }
}
