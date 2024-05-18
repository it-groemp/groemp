<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use \Validator;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        // Validator::make($collection->toArray(), [
        //     '*.pan_number' => 'required|alpha_num',
        //     '*.name' => 'required|alpha',
        //     '*.mobile' => 'required|numeric|digits:10',
        //     '*.email' => 'required|email',
        //     '*.designation' => 'required',
        //     '*.company' => 'required',
        //     '*.benefit_amount' => 'required|numeric'
        // ])->validate();

        // echo "hi";

        foreach ($collection as $row){
            Employee::create([
                "pan_number" => $row["pan_number"],
                "name" => $row["name"],
                "mobile" => $row["mobile"],
                "email" => $row["email"],
                "designation" => $row["designation"],
                "company" => $row["company"],
                "benefit_amount" => $row["benefit_amount"]
            ]);
        }
    }
}
