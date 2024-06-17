<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\Admin;
use App\Models\Company;
use App\Models\ResetPassword;
use App\Models\WorkflowApproval;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Mail\ChangePasswordMail;

use App\Imports\EmployeeAddImport;
use App\Imports\EmployeeBenefitAddImport;
use App\Imports\EmployeeBenefitUpdateImport;
use Excel;

class EmployeeController extends Controller
{
    public function login(){
        return view("login");
    }
    
    public function employeeLogin(){
        return view("employee.login");
    }

    public function logout(){
        Session::forget("employee");
        Session::forget("mobile");
        return redirect("/");
    }

    public function verifyEmployee(Request $request){
        $request->validate([
            'pan' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/'
        ]);

        $pan = Str::upper(request("pan"));
        $password = request("password");

        Log::info("verifyEmployee(): Login for ".$pan);

        $employee = Employee::where("pan_number", $pan)->first();
        if($employee==null) {
            $error="Employee does not exists. Please register first.";
        }
        else if (password_verify($password,$employee->password)){
            Session::put("employee",$employee->id);
            Log::info("verifyEmployee(): Logging successful for employee: ".$employee);
            return redirect("/profile");
        }
        else{
            $error="PAN and Password doesn't match. Please try again";
        }
        return redirect("/employee-login")->withErrors(["errors"=>$error]);
    }

    public function forgotPassword(){
        return view("employee.forgot-password");
    }

    public function sendPasswordLink(Request $request){
        $request->validate([
            'pan' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'
        ]);
        
        $pan = Str::upper($request->pan);

        Log::info("sendPasswordLink(): Send Password Link for Employee: ".$pan);

        $employee = Employee::where("pan_number", $pan)->first();
        if($employee==null){
            $error="Employee does not exists. Please register first.";
        }
        else{
            $token = Str::random(20);
            $email = $employee->email;
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
            $link=config("app.url")."/reset-password/$token";
            Mail::to($email)->send(new ResetPasswordMail($employee->name,$link));
            Log::info("sendPasswordLink(): Reset password Link sent to ".$email);    
            
            return redirect()->back()->with("success","Reset password link has been sent to the email id");
        }
        return redirect()->back()->with("error",$error);     
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where("token",$token)->first();
        if($resetPassword!=null){
            $email = $resetPassword->email;
            $pan_number = Employee::where("email",$email)->first()->pan_number;
            $resetPassword->delete();
            Session::put("pan",$pan_number);
            Log::info("resetPassword(): Password reset link successful for ".$email);
            return redirect("/display-change-password");
        }
    }

    public function displayChangePassword(){
        return view("employee.change-password");
    }

    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/',
            'cnfm_password' => 'required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/'
        ]);

        $pan = Session::get("pan");
        $password = $request->password;
        $cnfm_password = $request->cnfm_password;

        if($password!=$cnfm_password){
            Log::error("updatePassword(): Error occurred while updating password for ".$pan." Error: Both the passwords do not match");
            return redirect()->back()->withErrors(["errors"=>"Both the passwords do not match"]);
        }
        else{
            $employee = Employee::where("pan_number",$pan)->first();
            $employee->password = password_hash($password,PASSWORD_DEFAULT);
            $employee->update();
            $email=$employee->email;
            Session::forget("pan");
            Session::put("employee",$employee->id);
            Mail::to($email)->send(new ChangePasswordMail($employee->name));
            Log::info("updatePassword(): Password updated for employee ".$employee." and mail sent to ".$email);
            return redirect("/profile");
        }
    }

    public function profile(){
        $id = Session::get("employee");
        if($id!=null){
            $employee = Employee::find($id);
            return view("employee.profile")->with("employee",$employee);
        }
        else{
            return redirect("/employee-login");
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
