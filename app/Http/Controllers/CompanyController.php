<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Address;
use App\Models\CostCenter;
use App\Models\Workflow;
use App\Models\WorkflowApproval;
use App\Models\Benefit;
use App\Models\CompanyBenefit;

use App\Imports\CompanyImport;
use App\Imports\CostCenterImport;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Mail\QueryMail;
use App\Mail\ApproverCompanyBenefitsMail;

use Excel;

class CompanyController extends Controller
{
    public function companyDetailsAdmin(){
        if((new AdminController())->checkAdminSession()){
            $companies = Company::leftJoin("companies as sis","companies.group_company_code","=","sis.pan")
            ->orderBy("companies.group_company_code")
            ->get(["companies.pan as pan", "companies.name as name", "sis.name as group_company_name","companies.mobile as mobile", "companies.email as email"]);
            Log::info("companyDetailsAdmin(): Get all company details: ".$companies);
            return view("admin.company.company-details-admin")->with("companies",$companies);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function companyDetailsEmployer(){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::find($admin_id);
            $company_pan = $admin->company;
            $group_company = Company::where("pan",$company_pan)->first();
            if($group_company==null){
                Log::info("companyDetailsEmployer(): When company doesn't exists");
                return view("admin.company.company-details-employer")->with("group_company",$group_company);
            }
            else{
                $group_address = Address::where("company",$company_pan)->get();
                $sister_company = Company::where("group_company_code",$company_pan)->get();
                Log::info("companyDetailsEmployer(): Group company details: ".$group_company);
                Log::info("companyDetailsEmployer(): Address details for group company: ".$group_address);
                Log::info("companyDetailsEmployer(): Sister company details: ".$sister_company);
                $address_company = array();
                foreach($sister_company as $sis){
                    $sis_pan = $sis->pan;
                    $addresses = Address::where("company",$sis_pan)->get();
                    $arr = array();
                    foreach($addresses as $address){
                        array_push($arr,$address);
                    }
                    $address_company = array_merge($address_company,array($sis_pan=>$arr));
                    Log::info("companyDetailsEmployer(): Sister company address details: ".$sis_pan);
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
            Log::info("registerCompany(): Register Company Page loaded");
            return view("admin.company.register-company");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveCompanyDetails(Request $request){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $request->validate([
                "uploadFile" => "required|mimes:xlsx,xls",
            ]);    
            Excel::import(new CompanyImport, $request->file("uploadFile"));
            Log::info("saveCompanyDetails(): Company details uploaded");
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
            Log::info("ccDetails(): Get Cost Center details for all the companies: ".$ccDetails);
            return view("admin.company.cc-details")->with("ccDetails",$ccDetails)->with("approval_status",null);
        }
        else if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $approval_status = WorkflowApproval::join("companies","workflow_approval.company","companies.pan")
                                ->where("companies.pan",$admin->company)
                                ->orWhere("companies.group_company_code",$admin->company)
                                ->where("approval_for","Cost Center")
                                ->get(["companies.name as company_name","workflow_approval.approver_email as approver_email"]);
            $ccDetails = CostCenter::join("companies","cost_centers.company","companies.pan")
            ->where("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
            ->get(["id","company","name","cc1","cc2","cc3","cc4","cc5","cc6","cc7","cc8","cc9","cc10"]);
            Log::info("ccDetails(): Get Cost Center details for group and sister companies. ".$ccDetails);
            Log::info("ccDetails(): Get approval status for group and sister companies. ".$approval_status);
            return view("admin.company.cc-details")->with("ccDetails",$ccDetails)->with("approval_status",$approval_status);
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function updateCCDetails($id){
        if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $cost_center = CostCenter::where("id",$admin_id)->first();
            for($i=1;$i<=10;$i++){
                $name = "cc".$i;
                $cost_center->$name = Str::upper(request("CC".$i));
            }  
            $cost_center->updated_by = $admin->email; 
            $cost_center->update();     
            Log::info("updateCCDetails(): Cost Center details updated for ".$cost_center." by admin: ".$admin->email);
            return redirect("/cc-details");
        }
    }

    public function saveCCDetails(Request $request){
        if((new AdminController())->checkEmployerSession()){
            $request->validate([
                "uploadFile" => "required|mimes:xlsx,xls",
            ]);
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            Excel::import(new CostCenterImport, $request->file("uploadFile"));
            Log::info("saveCCDetails(): Uploaded cost center details for group and sister companies by admin: ".$admin->email);
            return redirect("/cc-details");
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function workflowDetails(){
        $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
        if((new AdminController())->checkAdminSession()){
            $workflow = Workflow::join("companies","workflows.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->get(["id","company","approver1","approver2","approver3","admin"]);
            $workflow_list = $workflow->pluck("company")->toArray();
            $company_list = Company::whereNotIn("pan",$workflow_list)->pluck("pan")->toArray();
            $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
            return view("admin.company.workflow-details")->with("workflow",$workflow)->with("company_list",$company_list)->with("admin_list",$admin_list);
        }
        else if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $workflow = Workflow::join("companies","workflows.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get(["id","company","approver1","approver2","approver3","admin"]);                        
            $workflow_list = $workflow->pluck("company")->toArray();
            $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
            $company_list = Company::where("companies.to_date",null)->whereNotIn("pan",$workflow_list)
                        ->where("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)->pluck("pan")->toArray();
            $admin_list = Admin::where("role","Admin")->pluck("email")->toArray();
            Log::info("workflowDetails(): Workflow details for employer requested by ".$admin->email." Workflow details: ".$workflow);
            return view("admin.company.workflow-details")->with("workflow",$workflow)->with("company_list",$company_list)->with("admin_list",$admin_list);
        }
        else{
            return redirect("/admin/login");
        }   
    }

    public function saveWorkflow(Request $request){
        if((new AdminController())->checkEmployerSession() || (new AdminController())->checkAdminSession()){
            $request->validate([
                "approver1" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
                "approver2" => "nullable|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
                "approver3" => "nullable|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            ]); 
            
            $company = Str::upper(request("company"));
            $approver1 = Str::lower(request("approver1"));
            $approver2 = Str::lower(request("approver2"));
            $approver3 = Str::lower(request("approver3"));
            $approver_admin = request("admin");
            
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $workflow = new Workflow();
            $workflow->company = $company;
            $workflow->approver1 = $approver1;
            $workflow->approver2 = $approver2;
            $workflow->approver3 = $approver3;
            $workflow->admin = $approver_admin;
            $workflow->created_by = $admin->email;
            $workflow->updated_by = $admin->email;
            $workflow->save();
            Log::info("saveWorkflow(): Workflow details added for company: ".$workflow." by admin: ".$admin->email);
            return redirect("/workflow-details");
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function updateWorkflow(){
        if((new AdminController())->checkEmployerSession() || (new AdminController())->checkAdminSession()){
            $request->validate([
                "approver1-edit" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
                "approver2-edit" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
                "approver3-edit" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            ]); 
            
            $company = Str::upper(request("company-edit"));
            $approver1 = Str::lower(request("approver1-edit"));
            $approver2 = Str::lower(request("approver2-edit"));
            $approver3 = Str::lower(request("approver3-edit"));
            $approver_admin = request("admin");
            
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $workflow = Workflow::where("company",$company)->first();
            $workflow->approver1 = $approver1;
            $workflow->approver2 = $approver2;
            $workflow->approver3 = $approver3;
            $workflow->updated_by = $admin->email;
            $workflow->update();
            Log::info("updateWorkflow(): Workflow details updated for company: ".$workflow." by admin: ".$admin->email);
            return redirect("/workflow-details");
        }
        else{
            return redirect("/admin/login");
        } 
    }

    public function companyBenefitsDetails(){
        if((new AdminController())->checkAdminSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $benefits = CompanyBenefit::join("companies","company_benefits.company","companies.pan")
                        ->where("companies.to_date",null)
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get();
            $benefits_list = Benefit::all();       
            Log::info("companyBenefitsDetails(): Get company selected benefit list for all companies: ".$benefits_list);
            return view("admin.company.company-benefits-details")->with("benefits",$benefits)->with("benefits_list",$benefits_list);
        }
        else if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $benefits = CompanyBenefit::join("companies","company_benefits.company","companies.pan")
                        ->orWhere("companies.pan",$admin->company)->orWhere("companies.group_company_code",$admin->company)
                        ->get();
            $benefits_list = Benefit::all();             
            Log::info("companyBenefitsDetails(): Get company selected benefit list for group and sister companies by employer: ".$admin->email." Benefits List: ".$benefits);  
            return view("admin.company.company-benefits-details")->with("benefits",$benefits)->with("benefits_list",$benefits_list);
        }
        else{
            return redirect("/admin/login");
        } 
    }

    public function addCompanyBenefit(){
        if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")
                        ->orderBy("categories.name")
                        ->orderBy("benefits.name")
                        ->get(['benefits.id as id','benefits.name as name','categories.name as category_name']);
            Log::info("addCompanyBenefit(): Add company benefits for company ".$admin->company." by employer:".$admin->email);
            return view("admin.company.add-company-benefits")->with("benefits",$benefits);
        }
        else{
            return redirect("/admin/login");
        } 
    }

    public function saveCompanyBenefit(Request $request){
        if((new AdminController())->checkEmployerSession()){
            $request->validate([
                "benefit" => "required",
            ]); 
            $benefits = request("benefit");
            $admin_id = Session::get("admin_id");
            $admin = Admin::find($admin_id);
            $company_pan = $admin->company;
            $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
            foreach($company_list as $company){
                $company_benefit = new CompanyBenefit();
                $company_benefit->company = $company;
                $company_benefit->benefits = json_encode($benefits);
                $company_benefit->created_by = $admin->email;
                $company_benefit->updated_by = $admin->email;
                $company_benefit->save();
                Log::info("saveCompanyBenefit(): Save company benefits for company ".$company_benefit." by employer:".$admin->email);
            }
            return redirect("/company-benefit-details");
        }
    }

    public function editCompanyBenefit($id){
        if((new AdminController())->checkEmployerSession()){
            $company_benefit = CompanyBenefit::find($id);
            $admin_id = Session::get("admin_id");
            $admin = Admin::find($id);
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")
                        ->orderBy("categories.name")
                        ->orderBy("benefits.name")
                        ->get(['benefits.id as id','benefits.name as name','categories.name as category_name']);
            Log::info("addCompanyBenefit(): Edit company benefits for company ".$company_benefit." by employer:".$admin->email);            
            return view("admin.company.edit-company-benefits")->with("company_benefit",$company_benefit)->with("benefits",$benefits);
        }
    }

    public function updateCompanyBenefit(Request $request){
        if((new AdminController())->checkAdminSession() || (new AdminController())->checkEmployerSession()){
            $request->validate([
                "benefit" => "required",
            ]); 
            $benefits = request("benefit");
            $id = request("id");
            $admin_id = Session::get("admin_id");
            $admin = Admin::find($id);
            $company_benefit = CompanyBenefit::find($id);
            $company_benefit->benefits = json_encode($benefits);
            $company_benefit->created_by = $admin->email;
            $company_benefit->updated_by = $admin->email;
            $company_benefit->update();
            Log::info("updateCompanyBenefit(): Save company benefits for company ".$company_benefit." by employer:".$admin->email);
            return redirect("/company-benefit-details");
        }
    }

    public function submitQuery(Request $request){
        $this->validate($request, [
            "name" => "required|regex:/^[a-zA-Z ]+$/",
            "email" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            "query" => "required"
        ]);
        $name = request("name");
        $email = request("email");
        $query = request("query");
        Mail::to(config("app.contact"))->send(new QueryMail($name,$email,$query));
        Session::put("success","Your query has been sent successfully to our team. They will contact you shortly.");
        return redirect("/contact-us");
    }
}