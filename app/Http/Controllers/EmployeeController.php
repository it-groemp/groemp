<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\Employee;
use App\Models\Otp;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;

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

    public function sendOtp(){
        $mobile = request("mobile");
        $employee = Employee::where("mobile",$mobile)->first();
        if($employee==null){
            return redirect()->back()->with("error","Employee doesn't exists");
        }
        $this->generateOtp($mobile);
        return redirect()->back();
    }

    public function verifyOtp(Request $request){
        $this->validate($request, [
            "otp" => "required|numeric|digits:6",
        ]);
        $mobile = Session::get("mobile");
        $otp = request("otp");
        $row=$this->checkOtp($mobile, $otp);
        if($row==null){
            $error = "OTP Invalid";
            return redirect()->back()->with("error",$error);
        }
        else{
            $employee = Employee::where("mobile",$mobile)->where("to_date",null)->first()->pluck("id");
            Session::put("emp-id",$employee->id);
            Session::forget("otpModal");
            return redirect("/profile");
        }
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

    public function generateOtp($mobile){
        $otp = new Otp();
        $otp->type = $mobile;
        $otp->otp = random_int(100000, 999999);
        Otp::where("type", $mobile)->delete();
        $otp->save();
        Session::put("mobile",$mobile);
        Session::put("otpModal","yes");
    }

    public function checkOtp($mobile, $otp){
        $row = Otp::where("type", $mobile)->where("otp", $otp)->first();
        return $row;
    }

    public function employeeBenefitsAdmin(){
        $employee_benefits=[];
        if((new AdminController())->checkAdminSession()){
            $employee_benefits = EmployeeBenefit::all();
            return view("admin.employee.employee-benefits")->with("employee_benefits",$employee_benefits);
        }
        else if((new AdminController())->checkEmployerSession()){
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $company_pan = $admin->company;
            $company_list = Company::where("pan",$company_pan)->orWhere("group_company_code",$company_pan)->pluck("pan")->toArray();
            $employee_benefits = EmployeeBenefit::whereIn("company",$company_list)->get();
            return view("admin.employee.employee-benefits")->with("employee_benefits",$employee_benefits);
        }
    }

    public function uploadEmployeeBenefits(Request $request){
        $request->validate([
            'uploadAddFile' => 'required|mimes:xlsx,xls',
        ]);
        Excel::import(new EmployeeBenefitAddImport, $request->file("uploadAddFile"));
        return redirect("/employee-benefits-admin");
    }

    public function updateEmployeeBenefits(Request $request){
        $request->validate([
            'uploadEditFile' => 'required|mimes:xlsx,xls',
        ]);
        Excel::import(new EmployeeBenefitUpdateImport, $request->file("uploadEditFile"));
        return redirect("/employee-benefits-admin");
    }
}
