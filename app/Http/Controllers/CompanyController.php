<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\City;
use App\Models\State;
use App\Models\Company;

use App\Imports\CompanyImport;

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
        //$company = Company::distinct("pan","name","group_company_code")->get();
        return view("admin.company.company-details-employer");
    }

    public function registerCompany(){
        return view("admin.company.register-company");
    }

    public function saveCompanyDetails(Request $request){
        $request->validate([
            'uploadFile' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CompanyImport, $request->file("uploadFile"));
    }
}