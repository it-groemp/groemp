<?php

namespace App\Imports;

use App\Models\Brand;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BrandImport implements ToCollection, WithHeadingRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function Collection(Collection $collection)
    {
        foreach ($collection as $row){
            Brand::create([
                "name" => $row["name"],
                "benefit_name" => $row["benefit_name"],
                "image_name" => $row["image"],
                "created_by" => $row["created_by"],
                "updated_by" => $row["updated_by"]
            ]);
        }
    }
}
