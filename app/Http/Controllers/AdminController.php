<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\Admin;
use App\Models\Employee;

use App\Imports\EmployeeImport;

use Excel;

class AdminController extends Controller
{
    public function saveAdmin(){
        $email = request("email");
        $name = request("name");
        $mobile = request("mobile");
        $pan_number = request("pan");
        $role = request("role");
        $error = "";
        if($name=="" || !preg_match ("/^[a-zA-Z .]+$/",$name)){
            $error = "Name should contain only Capital, Small Letters, Spaces and Dot Allowed";
        }
        if($email=="" || !preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$email)){
            $error = $error."<br/> Please enter a valid email address";
        }
        if($mobile=="" || !preg_match ("/[6-9]{1}[0-9]{9}/",$mobile)){
            $error = "Please enter a valid mobile number";
        }
        if($pan_number=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan_number)){
            $error = $error."<br/> Please enter a valid PAN";
        }
        if($error==""){
            $admin = new Admin();
            $admin->name = $name;
            $admin->mobile = $mobile;
            $admin->email = $email;
            $admin->pan_number = $pan_number;
            $admin->role = $role;
            $admin->save();
            return redirect("/admin/add-admin");
        }
        else{
            return redirect()->back()->with("error",$error);
        }
    }

    public function adminLogout(){      
        Session::forget("user_id");
        Session::forget("role");
        return redirect("/admin/login");
    }

    public function sendAdminOtp(){
        $mobile = request("mobile");
        $admin = Admin::where("mobile",$mobile)->first();
        if($admin==null){
            return redirect()->back()->with("errors","Employee doesn't exists");
        }
        (new EmployeeController())->generateOtp($mobile);
        return redirect()->back();
    }

    public function verifyAdminOtp(Request $request){
        $this->validate($request, [
            "otp" => "required|numeric|digits:6",
        ]);
        $mobile = Session::get("mobile");
        $otp = request("otp");
        //$row=(new EmployeeController())->checkOtp($mobile, $otp);
        //update on live
        $row=null;
        if($otp=="123456"){            
            $row="yes";
        }
        if($row==null){
            $error = "OTP Invalid";
            return redirect()->back()->with("error",$error);
        }
        else{
            Session::forget("otpModal");
            $admin = Admin::where("mobile",$mobile)->first();
            $company = Company::where("pan",$admmin->pan_number)->get();
            if($company!=null){
                $error = "The company has ceased its operations with us.";
                return redirect()->back()->with("error",$error);
            }
            $role=$admin->role;
            Session::put("user_id",$admin->id);
            Session::put("role",$admin->role);
            return redirect("/employee-details");
        }
    }

    public function checkAdminSession(){
        if(Session::get("user_id")!="" && Session::get("role")=="Admin"){
            return true;
        }
        else{
            return false;
        }
    }

    public function checkEmployerSession(){
        if(Session::get("user_id")!="" && Session::get("role")=="Employer"){
            return true;
        }
        else{
            return false;
        }
    }

    public function adminLogin(){     
        return view("admin.employee.login");
    }

    public function addAdmin(){
        if($this->checkAdminSession()){
            return view("admin.employee.add-admin");
        }
    }

    public function employeeDetails(){
        if($this->checkAdminSession() || $this->checkEmployerSession()){
            $role = Session::get("role");
            $id = Session::get("user_id");
            $user = Admin::where("id",$id)->first();
            $mobile = $user->mobile;
            $employees = [];
            if($role == "Admin"){
                $employees = Employee::all();
            }
            else if($role == "Employer"){
                $company = Admin::where("mobile",$mobile)->first()->value("pan_number");
                $employees = Employee::join("companies","employees.company","=","companies.pan")->where("employees.company",$company)
                            ->orWhere("companies.group_company_code",$company)
                            -andWhere("companies.from_date",null)
                            ->get(["employees.pan_number","employees.name","employees.mobile","employees.email","employees.designation","employees.benefit_amount"]);
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
