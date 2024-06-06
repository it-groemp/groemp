<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Address;
use App\Models\CostCenter;
use App\Models\Workflow;
use App\Models\Benefit;
use App\Models\CompanyBenefit;

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

    public function workflowDetails(){
        if((new AdminController())->checkAdminSession()){
            $workflow = Workflow::join("companies","workflows.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->get(["id","company","approver1","approver2","approver3","admin"]);
            $company_list = Company::all()->pluck("pan")->toArray();
            $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
            return view("admin.company.workflow-details")->with("workflow",$workflow)->with("company_list",$company_list)->with("admin_list",$admin_list);
        }
        else if((new AdminController())->checkEmployerSession()){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $workflow = Workflow::join("companies","workflows.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get(["id","company","approver1","approver2","approver3","admin"]);
            $company_list = Company::all()->pluck("pan")->toArray();
            $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
            return view("admin.company.workflow-details")->with("workflow",$workflow)->with("company_list",$company_list)->with("admin_list",$admin_list);
        }
        else{
            return redirect("/admin/login");
        }   
    }

    public function saveWorkflow(){
        $company = request("company");
        $approver1 = request("approver1");
        $approver2 = request("approver2");
        $approver3 = request("approver3");
        $approver_admin = request("admin");
        $errors="";
        if($approver1=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver1)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($approver2=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver2)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($approver3=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver3)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($errors!=""){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $workflow = new Workflow();
            $workflow->company = $company;
            $workflow->approver1 = $approver1;
            $workflow->approver2 = $approver2;
            $workflow->approver3 = $approver3;
            $workflow->admin = $approver_admin;
            $workflow->created_by = $admin->email;
            $workflow->updated_by = $admin->email;
            $workflow->save();
            return redirect("/workflow-details");
        }
        else{
            return redirect()->back()->with("errors",$errors);
        }
    }

    public function updateWorkflow(){
        $company = request("company-edit");
        $approver1 = request("approver1-edit");
        $approver2 = request("approver2-edit");
        $approver3 = request("approver3-edit");
        $errors="";
        if($approver1=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver1)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($approver2=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver2)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($approver3=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$approver3)){
            $errors = $errors."<br/> Please enter a valid email address";
        }
        if($errors!=""){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $workflow = Workflow::where("company",$company)->first();
            $workflow->approver1 = $approver1;
            $workflow->approver2 = $approver2;
            $workflow->approver3 = $approver3;
            $workflow->updated_by = $admin->email;
            $workflow->update();
            return redirect("/workflow-details");
        }
        else{
            return redirect()->back()->with("errors",$errors);
        }
    }

    public function companyBenefitsDetails(){
        if((new AdminController())->checkAdminSession()){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $benefits = CompanyBenefit::join("companies","company_benefits.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get();
            $benefits_list = Benefit::all();       
            return view("admin.company.company-benefits-details")->with("benefits",$benefits)->with("benefits_list",$benefits_list);
        }
        else if((new AdminController())->checkEmployerSession()){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $benefits = CompanyBenefit::join("companies","company_benefits.company","companies.pan")
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get();
            $benefits_list = Benefit::all();         
            return view("admin.company.company-benefits-details")->with("benefits",$benefits)->with("benefits_list",$benefits_list);
        }
    }

    public function addCompanyBenefit(){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")
                        ->orderBy("categories.name")
                        ->orderBy("benefits.name")
                        ->get(['benefits.id as id','benefits.name as name','categories.name as category_name']);
            return view("admin.company.add-company-benefits")->with("benefits",$benefits);
        }
    }

    public function saveCompanyBenefit(Request $request){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $request->validate([
                'benefit' => 'required',
            ]); 
            $benefits = request("benefit");
            $id = Session::get("admin_id");
            $admin = Admin::find($id);
            $company_pan = $admin->company;
            $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
            foreach($company_list as $company){
                $company_benefit = new CompanyBenefit();
                $company_benefit->company = $company;
                $company_benefit->benefits = json_encode($benefits);
                $company_benefit->created_by = $admin->email;
                $company_benefit->updated_by = $admin->email;
                $company_benefit->save();
            }
            return redirect("/company-benefit-details");
        }
    }

    public function editCompanyBenefit($id){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $company_benefit = CompanyBenefit::find($id);
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")
                        ->orderBy("categories.name")
                        ->orderBy("benefits.name")
                        ->get(['benefits.id as id','benefits.name as name','categories.name as category_name']);
            return view("admin.company.edit-company-benefits")->with("company_benefit",$company_benefit)->with("benefits",$benefits);
        }
    }

    public function updateCompanyBenefit(Request $request){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $request->validate([
                'benefit' => 'required',
            ]); 
            $benefits = request("benefit");
            $id = request("id");
            $id = Session::get("admin_id");
            $admin = Admin::find($id);
            $company_benefit = CompanyBenefit::find($id);
            $company_benefit->benefits = json_encode($benefits);
            $company_benefit->created_by = $admin->email;
            $company_benefit->updated_by = $admin->email;
            $company_benefit->update();
            return redirect("/company-benefit-details");
        }
    }
}