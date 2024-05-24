<?php

namespace App\Imports;

use App\Models\CostCenter;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CostCenterImport implements ToCollection, WithHeadingRow 
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $row){
            CostCenter::create([
                "company" => $row["company"],
                "cc1" => $row["cc1"],
                "cc2" => $row["cc2"] ?? "",
                "cc3" => $row["cc3"] ?? "",
                "cc4" => $row["cc4"] ?? "",
                "cc5" => $row["cc5"] ?? "",
                "cc6" => $row["cc6"] ?? "",
                "cc7" => $row["cc7"] ?? "",
                "cc8" => $row["cc8"] ?? "",
                "cc9" => $row["cc9"] ?? "",
                "cc10" => $row["cc10"] ?? "",
            ]);
        }
    }
}
