<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Address;
use App\Models\Admin;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

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
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $prev_pan="";
        foreach ($collection as $row){
            $curr_pan = Str::upper($row["pan"]);
            if($prev_pan!=$curr_pan){
                $company = new Company();
                $company->name = $row["name"] ?? "";
                $company->group_company_code = Str::upper($row["group_company_code"]) ?? null;
                $company->pan = $curr_pan ?? "";
                $company->mobile = $row["mobile"] ?? "";
                $company->email = $row["email"] ?? "";    
                $company->created_at = Carbon::now()->toDateTimeString();
                $company->created_by = $admin->email;
                $company->updated_at = Carbon::now()->toDateTimeString();
                $company->updated_by = $admin->email;
                $company->save();
            }

            $prev_pan = $curr_pan;         
            $address = new Address();
            $address->company = $curr_pan;
            $address->state = $row["state"] ?? "";
            $address->city = $row["city"] ?? "";
            $address->pincode = $row["pincode"] ?? "";
            $address->created_at = Carbon::now()->toDateTimeString();
            $address->created_by = $admin->email;
            $address->updated_at = Carbon::now()->toDateTimeString();
            $address->updated_by = $admin->email;
            $address->save();
        }
    }
}