<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;
use App\Models\WorkflowApproval;

use App\Imports\EmployeeAddImport;
use App\Imports\EmployeeBenefitAddImport;
use App\Imports\EmployeeBenefitUpdateImport;
use Excel;

class EmployeeController extends Controller
{
    public function login(){
        return view("employee.login");
    }

    public function logout(){
        Session::forget("employee");
        Session::forget("mobile");
        return redirect("/");
    }

    public function profile(){
        $mobile = Session::get("mobile");
        if($mobile!=null){
            $employee = Session::get("employee");
            if($employee==null){
                $employee = Employee::where("mobile",$mobile)->first();
                Session::put("employee",$employee);
            }
            return view("employee.profile")->with("employee",$employee);
        }
        else{
            return redirect("/login");
        }
    }

    public function employeeBenefitsAdmin(){
        $employee_benefits=[];
        if((new AdminController())->checkAdminSession()){
            $employee_benefits = EmployeeBenefit::all();
            Log::info("employeeBenefitsAdmin(): Employee Benefits view for all companies: ".$employee_benefits);
            return view("admin.employee.employee-benefits")->with("employee_benefits",$employee_benefits)->with("approval_status",null);
        }
        else if((new AdminController())->checkEmployerSession()){
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $company_pan = $admin->company;
            $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
            $employee_benefits = EmployeeBenefit::whereIn("company",$company_list)->get();
            $approval_status = WorkflowApproval::join("companies","workflow_approval.company","companies.pan")
                                ->where("companies.pan",$admin->company)
                                ->orWhere("companies.group_company_code",$admin->company)
                                ->where("approval_for","Employees")
                                ->orWhere("approval_for","Employees Benefit")
                                ->get(["companies.name as company_name","workflow_approval.approver_email as approver_email"]);
            Log::info("employeeBenefitsAdmin(): Employee Benefits view for company: ".$employee_benefits);
            Log::info("employeeBenefitsAdmin(): Approval List: ".$approval_status);
            return view("admin.employee.employee-benefits")->with("employee_benefits",$employee_benefits)->with("approval_status",$approval_status);
        }
    }

    public function uploadEmployeeBenefits(Request $request){
        $request->validate([
            'uploadAddFile' => 'required|mimes:xlsx,xls',
        ]);
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        Excel::import(new EmployeeBenefitAddImport, $request->file("uploadAddFile"));
        Log::info("uploadEmployeeBenefits(): Employee Benefits added for employees by admin: ".$admin->email);
        return redirect("/employee-benefits-admin");
    }

    public function updateEmployeeBenefits(Request $request){
        $request->validate([
            'uploadEditFile' => 'required|mimes:xlsx,xls',
        ]);
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        Excel::import(new EmployeeBenefitUpdateImport, $request->file("uploadEditFile"));
        Log::info("updateEmployeeBenefits(): Employee Benefits updated for employees by admin: ".$admin->email);
        return redirect("/employee-benefits-admin");
    }
}
