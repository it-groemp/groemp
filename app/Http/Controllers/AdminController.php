<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Company;
use App\Models\ResetPassword;

use App\Imports\EmployeeAddImport;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SetPasswordMail;
use App\Mail\ResetPasswordMail;
use App\Mail\UpdatePasswordAdminMail;

use Carbon\Carbon;

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
            $admin->company = $pan_number;
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

    public function verifyAdmin(Request $request){
        $pan = request("pan");
        $password = request("password");
        $error="";
        if($pan=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan)){
            $error = $error."<br/> Please enter a valid PAN";
        }
        if($password==null || !preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",$password)){
            $error = $error."<br/> Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters";
        }
        if($error==""){
            $admin = Admin::where("company", $pan)->first();
            if($admin==null) {
                $error="Admin does not exists. Please register first.";
            }
            else if (password_verify($password,$admin->password)){
                Session::put("user_id",$admin->id);
                Session::put("role",$admin->role);
                return redirect("/employee-details");
            }
            else{
                $error="PAN and Password doesn't match. Please try again";
            }
        }
        else{
            return redirect()->back()->with("error",$error);
        }

        
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
            $company = Company::where("pan",$admin->company)->where("to_date","!=",null)->exists();
            if($company){
                $error = "The company has ceased its operations with us.";
                return redirect()->back()->with("error",$error);
            }
            $role=$admin->role;
            Session::put("user_id",$admin->id);
            Session::put("role",$admin->role);
            return redirect("/employee-details");
        }
    }

    public function setPassword($function){
        return view("admin.employee.set-password")->with("function",$function);
    }

    public function sendPasswordLink($function){
        $pan = request("pan");
        $error=null;
        if($pan=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan)){
            $error = $error."Please enter a valid PAN";
        }
        if($error==null){
            $admin = Admin::where("company", $pan)->first();
            if($admin==null){
                $error="Admin does not exists. Please register first.";
            }
            else{
                $token = Str::random(20);
                $email = $admin->email;
                $resetPassword=ResetPassword::where("email", $email)->first();

                if($resetPassword==null){
                    $resetPassword = new ResetPassword();
                    $resetPassword->email = $email;
                    $resetPassword->token = $token;
                    $resetPassword->save();
                }
                else{
                    $resetPassword->token = $token;
                    $resetPassword->update();
                }

                $link=env("APP_URL")."/reset-password/$token";
                if($function=="forgot"){
                    Mail::to($email)->send(new ResetPasswordMail($admin->name,$link));
                }
                else{
                    Mail::to($email)->send(new SetPasswordMail($admin->name,$link));
                }                
                return redirect()->back()->with("success","Reset password link has been sent to the email id");
            }
        }
        return redirect()->back()->with("error",$error);     
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where("token",$token)->first();
        if($resetPassword!=null){
            $email = $resetPassword->email;
            $company = Admin::where("email",$email)->first()->value("company");
            //$resetPassword->delete();
            Session::put("company",$company);
            return redirect("/display-change-password");
        }
    }

    public function displayChangePassword(){
        return view("admin.employee.change-password");
    }

    public function updatePassword(){
        $company = request("pan");
        $password = request("password");
        $cnfm_password = request("cnfm_password");
        $error="";
        if($password==null || !preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",$password)){
            $error = "Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters";
        }
        else if($cnfm_password==null || !preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",$cnfm_password)){
            $error = "Confirm Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters";
        }
        else if($password!=$cnfm_password){
            $error = "Both the passwords do not match";
        }
        else{
            $admin = Admin::where("company",$company)->first();
            $admin->password = password_hash($password,PASSWORD_DEFAULT);
            $admin->update();
            $email=$admin->email;
            Session::forget("company");
            Mail::to($email)->send(new UpdatePasswordAdminMail($admin->name));
            return redirect("/admin/login");
        }
        if($error!=null){
            return redirect()->back()->with("error",$error);
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
                $company = $user->company;
                $employees = Employee::join("companies","employees.company","=","companies.pan")
                            ->where("employees.company",$company)
                            ->orWhere("companies.group_company_code",$company)
                            ->where("companies.to_date",null)
                            ->where("employees.to_date",null)
                            ->get(["employees.id","employees.pan_number","employees.company","employees.name","employees.mobile","employees.email","employees.designation","employees.benefit_amount"]);
                //dd($employees);
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
        Excel::import(new EmployeeAddImport, $request->file("uploadFile"));
        return redirect("/employee-details");
    }

    public function updateEmployeeDetails($id){
        $email = request("email");
        $name = request("name");
        $mobile = request("mobile");
        $pan = request("pan");
        $designation = request("designation");
        $amount = request("amount");
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
        if($pan=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan)){
            $error = $error."<br/> Please enter a valid PAN";
        }
        if($designation==""){
            $error = $error."<br/> Please enter a valid Designation";
        }
        if($amount=="" || !preg_match("/^[0-9]+$/",$amount)){
            $error = $error."<br/> Please enter a valid Amount";
        }
        if($error==""){
            //$admin_id = Session::get("user_id");
            //$user = Admin::where("id",$admin_id)->first();
            $employee = Employee::where("id",$id)->first();
            $employee->pan_number = $pan;
            $employee->name = $name;
            $employee->mobile = $mobile;
            $employee->email = $email;
            $employee->designation = $designation;
            $employee->benefit_amount = $amount;
            //$employee->updated_by = $user->email;
            $employee->update();
            return redirect("/employee-details");
        }
        else{
            return redirect()->back()->with("error",$error);
        }
    }

    public function freezeEmployee($id){
        $employee = Employee::where("id",$id)->first();
        $employee->to_date = Carbon::now()->toDateTimeString();;
        $employee->update();
        return redirect("/employee-details");
    }
}