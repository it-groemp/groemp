<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Address;

use Illuminate\Support\Str;
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

    // public function sheets(): array
    // {
    //     return [
    //         "Group_Company" => $this,
    //     ];
    // }

    public function Collection(Collection $collection)
    {
        $prev_pan="";
        foreach ($collection as $row){
            $curr_pan = Str::upper($row["pan"]);
            if($prev_pan!=$curr_pan){
                Company::create([
                    "name" => $row["name"] ?? "",
                    "group_company_code" => Str::upper($row["group_company_code"]) ?? null,
                    "pan" => $curr_pan ?? "",
                    "mobile" => $row["mobile"] ?? "",
                    "email" => $row["email"] ?? ""
                ]);
            }
            $prev_pan = $curr_pan;         
            Address::create([
                "company" => $curr_pan,
                "state" => $row["state"] ?? "",
                "city" => $row["city"] ?? "",
                "pincode" => $row["pincode"] ?? ""
            ]);
        }
    }
}