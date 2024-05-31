<?php

namespace App\Imports;

use App\Models\CostCenter;
use App\Models\Admin;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Str;
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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        foreach($collection as $row){
            $cost_center = new CostCenter();
            $cost_center->company = Str::upper($row["company"]);
            $cost_center->cc1 = $row["cc1"];
            $cost_center->cc2 = $row["cc2"] ?? "";
            $cost_center->cc3 = $row["cc3"] ?? "";
            $cost_center->cc4 = $row["cc4"] ?? "";
            $cost_center->cc5 = $row["cc5"] ?? "";
            $cost_center->cc6 = $row["cc6"] ?? "";
            $cost_center->cc7 = $row["cc7"] ?? "";
            $cost_center->cc8 = $row["cc8"] ?? "";
            $cost_center->cc9 = $row["cc9"] ?? "";
            $cost_center->cc10 = $row["cc10"] ?? "";
            $cost_center->created_at = Carbon::now()->toDateTimeString();
            $cost_center->created_by = $admin->email;
            $cost_center->updated_at = Carbon::now()->toDateTimeString();
            $cost_center->updated_by = $admin->email;
            $cost_center->save();
        }
    }
}
