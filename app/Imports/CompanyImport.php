<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanyImport implements ToCollection, WithHeadingRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function sheets(): array
    {
        return [
            "Group_Company" => $this,
        ];
    }

    public function Collection(Collection $collection)
    {
        
        foreach ($collection as $row){
            Company::create([
                "name" => $row["name"] ?? "",
                "group_company_code" => $row["group_company_code"] ?? "",
                "pan" => $row["pan"] ?? "",
                "mobile" => $row["mobile"] ?? "",
                "email" => $row["email"] ?? "",
                "state" => $row["state"] ?? "",
                "city" => $row["city"] ?? ""
            ]);
        }
    }
}
