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
    public function companyDetails(){
        $company = Company::distinct("pan","name","group_company_code")->get();
        //dd($company);
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