<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\Employee;
use App\Models\Otp;

use App\Imports\EmployeeImport;

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
            return redirect()->back()->with("errors","Employee doesn't exists");
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

    public function checkAdminSession(){
        if(Session::get("email")!="" && Session::get("role")=="Admin"){
            return true;
        }
        else{
            return false;
        }
    }

    public function checkEmployerSession(){
        if(Session::get("email")!="" && Session::get("role")=="Employer"){
            return true;
        }
        else{
            return false;
        }
    }

    public function adminLogin(){     
        return view("admin.employee.login");
    }

    public function addAdminLogin(){
        if($this->checkAdminSession){
            return view("admin.employee.admin-login");
        }        
    }

    public function saveAdminLogin(){

    }

    public function adminLogout(){      
        Session::forget("email");
        Session::forget("role");
        return view("/admin/login");
    }

    public function sendAdminOtp(){
        $mobile = request("mobile");
        $employee = Employee::where("mobile",$mobile)->first();
        if($employee==null){
            return redirect()->back()->with("errors","Employee doesn't exists");
        }
        $this->generateOtp($mobile);
        return redirect()->back();
    }

    public function verifyAdminOtp(Request $request){
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
            Session::forget("otpModal");
            $employee = Employee::where("mobile",$mobile)->first();
            $role=$employee->role;
            if($role=="Admin" || $role=="Employer"){
                Session::put("email",$employee->email);
                Session::put("role",$employee->role);
            }
            return redirect("/employee-details");
        }
    }

    public function employeeDetails(){
        if($this->checkAdminSession()){
            $role = Session::get("role");
            $employees = null;
            if($role == "Admin"){
                $employees = Employee::all();
            }
            else if($role == "Employer"){
                $email = Session::get("email");
                $company = Employee::where("email",$email)->pluck("company");
                $employees = Employee::where("company",$company);
            }
            return view("/admin/employee/list-employees")->with("employees",$employees);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveEmployeeDetails(Request $request){
        $request->validate([
            'uploadFile' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new EmployeeImport, $request->file("uploadFile"));

        return redirect("/employee-details");
    }
}
