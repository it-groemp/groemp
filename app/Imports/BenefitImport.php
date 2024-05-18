<?php

namespace App\Imports;

use App\Models\Benefit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BenefitImport implements ToCollection, WithHeadingRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function Collection(Collection $collection)
    {
        foreach ($collection as $row){
            Benefit::create([
                "name" => $row["name"],
                "amount" => $row["amount"],
                "image_name" => $row["image"],
                "created_by" => $row["created_by"],
                "updated_by" => $row["updated_by"]
            ]);
        }
    }
}
