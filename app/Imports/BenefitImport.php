<?php

namespace App\Imports;

use App\Models\Benefit;
use App\Models\Admin;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        foreach ($collection as $row){
            $benefit = new Benefit();
            $benefit->name = $row["name"];
            $benefit->amount = $row["amount"];
            $benefit->image_name = $row["image"];
            $benefit->created_at = Carbon::now()->toDateTimeString();
            $benefit->created_by = $admin->email;
            $benefit->updated_at = Carbon::now()->toDateTimeString();
            $benefit->updated_by = $admin->email;
            $benefit->save();
        }
    }
}
