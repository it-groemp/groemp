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
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CompanyImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
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
        $company_pan = $admin->company;
        $role = $admin->role;
        $prev_pan="";
        foreach ($collection as $row){
            $row = $row->toArray();
            $curr_pan = array_key_exists("pan", $row) ? Str::upper($row["pan"]) : "";
            $group_company_pan = array_key_exists("group_company_pan", $row) ? Str::upper($row["group_company_pan"]) : "";
            if($curr_pan!=""){
                if(($curr_pan == $company_pan) || (($group_company_pan == $company_pan) || $role=="Admin")){
                    if($prev_pan!=$curr_pan){
                        $company = new Company();
                        $company->name = $row["name"] ?? "";
                        $company->group_company_code = $group_company_pan;
                        $company->pan = $curr_pan ?? "";
                        $company->mobile = $row["admin_mobile"] ?? "";
                        $company->email = $row["admin_email"] ?? "";    
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
    }
}