<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Admin;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        foreach ($collection as $row){
            $brand = new Brand();
            $brand->name = $row["name"];
            $brand->benefit_name = $row["benefit_name"];
            $brand->image_name = $row["image"];
            $brand->created_at = Carbon::now()->toDateTimeString();
            $brand->created_by = $admin->email;
            $brand->updated_at = Carbon::now()->toDateTimeString();
            $brand->updated_by = $admin->email;
            $brand->save();
        }
    }
}
