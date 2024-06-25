<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Address;
use App\Models\Admin;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class CompanyImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function Collection(Collection $collection)
    {
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        $company_pan = $admin->company;
        $role = $admin->role;
        $prev_pan="";
        foreach ($collection as $row){
            $row = $row->toArray();
            if (!isset($row["city"])) 
                continue;
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
                        Log::info("CompanyImport: Added company details of ".$company." added by admin: ".$admin->email);
                    }
                    $prev_pan = $curr_pan;         
                    $address = new Address();
                    $address->company = $curr_pan;
                    $address->state = $row["state"];
                    $address->city = $row["city"];
                    $address->pincode = $row["pincode"];
                    $address->created_at = Carbon::now()->toDateTimeString();
                    $address->created_by = $admin->email;
                    $address->updated_at = Carbon::now()->toDateTimeString();
                    $address->updated_by = $admin->email;
                    $address->save();
                    Log::info("CompanyImport: Added address details of ".$address." added by admin: ".$admin->email);
                }
            }                 
        }
    }

    public function rules(): array
    {
        return [
            "*.pan" => ["regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/","unique:companies,pan"],
            "*.admin_mobile" => ["regex:/[6-9]{1}[0-9]{9}/","unique:companies,mobile"],
            "*.admin_email" => ["regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/","unique:companies,email"],
            "*.state" =>["alpha"],
            "*.pincode" =>["numeric","digits:6"]
        ];
    }

    public function customValidationMessages()
    {
        return [
            "pan.regex" => "Company PAN is invalid",
            "pan.unique" => "Company PAN is already registered",
            "admin_mobile.regex" => "Admin mobile number is invalid",
            "admin_mobile.unique" => "Admin mobile is already registered",
            "admin_email.regex" => "Admin email is invalid",
            "admin_email.unique" => "Admin email is already registered",
            "state.alpha" => "State can have only alphabets",
            "pincode.numeric" => "Pincode can have only numbers",
            "pincode.digits" => "Pincode can have exactly 6 digits",
        ];
    }
}