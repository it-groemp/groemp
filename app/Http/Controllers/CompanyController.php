<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Address;
use App\Models\CostCenter;

use App\Imports\CompanyImport;
use App\Imports\CostCenterImport;

use Excel;

class CompanyController extends Controller
{
    public function companyDetailsAdmin(){
        if((new AdminController())->checkAdminSession()){
            $companies = Company::leftJoin("companies as sis","companies.group_company_code","=","sis.pan")
            ->orderBy("companies.group_company_code")
            ->get(["companies.pan as pan", "companies.name as name", "sis.name as group_company_name","companies.mobile as mobile", "companies.email as email"]);
            return view("admin.company.company-details-admin")->with("companies",$companies);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function companyDetailsEmployer(){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $id = Session::get("admin_id");
            $admin = Admin::find($id);
            $company_pan = $admin->company;
            $group_company = Company::where("pan",$company_pan)->first();
            if($group_company==null){
                return view("admin.company.company-details-employer")->with("group_company",$group_company);
            }
            else{
                $group_address = Address::where("company",$company_pan)->get();
                $sister_company = Company::where("group_company_code",$company_pan)->get();
                $address_company = array();
                foreach($sister_company as $sis){
                    $sis_pan = $sis->pan;
                    $addresses = Address::where("company",$sis_pan)->get();
                    $arr = array();
                    foreach($addresses as $address){
                        array_push($arr,$address);
                    }
                    $address_company = array_merge($address_company,array($sis_pan=>$arr));
                }
                
                return view("admin.company.company-details-employer")
                    ->with("admin",$admin)
                    ->with("group_company",$group_company)
                    ->with("group_address",$group_address)
                    ->with("sister_company",$sister_company)
                    ->with("address_company",$address_company);
            }
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function registerCompany(){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            return view("admin.company.register-company");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveCompanyDetails(Request $request){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $request->validate([
                'uploadFile' => 'required|mimes:xlsx,xls',
            ]);    
            Excel::import(new CompanyImport, $request->file("uploadFile"));
            if((new AdminController())->checkAdminSession()){
                return redirect("/company-details-admin");
            }
            else{
                return redirect("/company-details-employer");
            }
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function ccDetails(){
        $ccDetails=[];
        if((new AdminController())->checkAdminSession()){
            $ccDetails = CostCenter::join("companies","cost_centers.company","companies.pan")
            ->get(["id","company","name","cc1","cc2","cc3","cc4","cc5","cc6","cc7","cc8","cc9","cc10"]);
            return view("admin.company.cc-details")->with("ccDetails",$ccDetails);
        }
        else if((new AdminController())->checkEmployerSession()){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $ccDetails = CostCenter::join("companies","cost_centers.company","companies.pan")
            ->where("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
            ->get(["id","company","name","cc1","cc2","cc3","cc4","cc5","cc6","cc7","cc8","cc9","cc10"]);
            return view("admin.company.cc-details")->with("ccDetails",$ccDetails);
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function updateCCDetails($id){
        $id = Session::get("admin_id");
        $admin = Admin::where("id",$id)->first();
        $cost_center = CostCenter::where("id",$id)->first();
        for($i=1;$i<=10;$i++){
            $name = "CC".$i;
            $cost_center->$name = request("CC".$i);
        }  
        $cost_center->updated_by = $admin->email; 
        $cost_center->update();     
        return redirect("/cc-details");
    }

    public function saveCCDetails(Request $request){
        $request->validate([
            'uploadFile' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CostCenterImport, $request->file("uploadFile"));

        return redirect("/cc-details");
    }
}