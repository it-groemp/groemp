<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Company;
use App\Models\ResetPassword;
use App\Models\WorkflowApproval;
use App\Models\PasswordBackupAdmin;

use App\Imports\EmployeeAddImport;
use App\Imports\EmployeeUpdateImport;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Mail\WelcomeAdminMail;
use App\Mail\SetPasswordAdminMail;
use App\Mail\ResetPasswordAdminMail;
use App\Mail\UpdatePasswordAdminMail;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use Excel;

class AdminController extends Controller
{
    public function saveAdmin(){
        $email = request("email");
        $name = request("name");
        $mobile = request("mobile");
        $pan_number = Str::upper(request("pan"));
        $company = Str::upper(request("company"));
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
        if($company=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$company)){
            $error = $error."<br/> Please enter a valid PAN";
        }
        if($error==""){
            $admin_id = Session::get("admin_id");
            $old_admin = Admin::where("id",$admin_id)->first();
            $admin = new Admin();
            $admin->name = $name;
            $admin->mobile = $mobile;
            $admin->email = $email;
            $admin->pan = $pan_number;
            $admin->company = $company;
            $admin->role = $role;
            $admin->password = password_hash("Groemp@1234",PASSWORD_DEFAULT);
            $admin->created_by = $old_admin->email;
            $admin->updated_by = $old_admin->email;
            $admin->save();
            Log::info("saveAdmin(): New admin created email: ".$admin." by admin: ".$old_admin->company);
            
            $token = Str::random(20);

            $resetPassword = new ResetPassword();
            $resetPassword->email = $email;
            $resetPassword->token = $token;
            $resetPassword->save();

            $link=config("app.url")."/reset-password-admin/$token";
            Mail::to($email)->send(new WelcomeAdminMail($admin->name,$link));

            return redirect("/admin/display-admin");
        }
        else{
            Log::error("saveAdmin(): Error occurred while creating new admin email: ".$email."   Error: ".$error);
            return redirect()->back()->with("error",$error);
        }
    }

    public function adminLogout(){      
        Session::forget("admin_id");
        Session::forget("role");
        return redirect("/admin/login");
    }

    public function verifyAdmin(Request $request){
        $pan = Str::upper(request("pan"));
        $password = request("password");
        $error="";
        
        Log::info("verifyAdmin(): Login for ".$pan);
        
        if($pan=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan)){
            $error = $error."<br/> Please enter a valid PAN";
        }
        if($password==null || !preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",$password)){
            $error = $error."<br/> Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters";
        }
        if($error==""){
            $admin = Admin::where("pan", $pan)->first();
            if($admin==null) {
                $error="Admin does not exists. Please register first.";
            }
            else if (password_verify($password,$admin->password)){
                Session::put("admin_id",$admin->id);
                Session::put("role",$admin->role);
                Log::info("verifyAdmin(): Logging successful for admin: ".$admin->company);
                return redirect("/employee-details");
            }
            else{
                $error="PAN and Password doesn't match. Please try again";
            }
        }
        Log::error("verifyAdmin(): Error has occurred while logging for ".$pan." Error: ".$error);
        return redirect()->back()->with("error",$error);
    }

    public function forgotPassword(){
        return view("admin.employee.forgot-password");
    }

    public function sendPasswordLink(){
        $pan = Str::upper(request("pan"));
        $error=null;
        Log::info("sendPasswordLink(): Send Password Link for: ".$pan);
        if($pan=="" || !preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",$pan)){
            $error = $error."Please enter a valid PAN";
        }
        if($error==null){
            $admin = Admin::where("pan", $pan)->first();
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
                $link=config("app.url")."/reset-password-admin/$token";
                Mail::to($email)->send(new ResetPasswordAdminMail($admin->name,$link));
                Log::info("sendPasswordLink(): Reset password Link sent to ".$email);     
                
                $char = Str::position($email, "@");
                return redirect()->back()->with("success","Reset password link has been sent to the email id: ".Str::mask($email, '*', 3, $char-3));
            }
        }
        return redirect()->back()->with("error",$error);     
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where("token",$token)->first();
        if($resetPassword!=null){
            $email = $resetPassword->email;
            $pan = Admin::where("email",$email)->first()->pan;
            $resetPassword->delete();
            Session::put("pan",$pan);
            Log::info("resetPassword(): Password reset link successful for ".$email);
            return redirect("/display-change-password-admin");
        }
    }

    public function displayChangePassword(){
        return view("admin.employee.change-password");
    }

    public function updatePassword(Request $request){
        $request->validate([
            "password" => "required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",
            "cnfm_password" => "required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"
        ]);
        $pan = Str::upper($request->pan);
        $password = $request->password;
        $cnfm_password = $request->cnfm_password;
        $error="";
        if($password!=$cnfm_password){
            $error = "Both the passwords do not match";
        }
        else{
            $password_list = PasswordBackupAdmin::where("pan",$pan)->pluck("password")->toArray();
            $hash_password = password_hash($password,PASSWORD_DEFAULT);
            if(count($password_list)>0 && in_array($hash_password, $password_list)){
                $error = "Please do not enter last 3 passwords";
            }
            else{
                $password_backup = new PasswordBackupAdmin();
                
                if(count($password_list)==3){
                    $backup = PasswordBackupAdmin::where("pan",$pan)->first();
                    $backup->delete();
                }
                
                $password_backup->pan = $pan;
                $password_backup->password = $hash_password;
                $password_backup->save();

                $admin = Admin::where("pan",$pan)->first();
                $admin->password = password_hash($password,PASSWORD_DEFAULT);
                $admin->update();
                $email=$admin->email;
                Session::forget("pan");
                Session::put("admin_id",$admin->id);
                Session::put("role",$admin->role);
                Mail::to($email)->send(new UpdatePasswordAdminMail($admin->name));
                Log::info("updatePassword(): Password updated for pan ".$pan." and mail sent to ".$email);
                return redirect("/employee-details");
            }
        }
        if($error!=""){
            Log::error("updatePassword(): Error occurred while updating password for PAN: ".$pan." Error: ".$error);
            return redirect()->back()->withErrors(["errors"=>"Both the passwords do not match"]);
        }
    }

    public function checkAdminSession(){
        if(Session::get("admin_id")!="" && Session::get("role")=="Admin"){
            return true;
        }
        else{
            return false;
        }
    }

    public function checkEmployerSession(){
        if(Session::get("admin_id")!="" && Session::get("role")=="Employer"){
            return true;
        }
        else{
            return false;
        }
    }

    public function adminLogin(){     
        return view("admin.employee.login");
    }

    public function displayAdmin(){
        if($this->checkAdminSession()){
            $admins = Admin::all();
            return view("admin.employee.display-admin")->with("admins",$admins);
        }
    }

    public function addAdmin(){
        if($this->checkAdminSession()){
            return view("admin.employee.add-admin");
        }
    }

    public function employeeDetails(){
        if($this->checkAdminSession() || $this->checkEmployerSession()){
            $role = Session::get("role");
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $mobile = $admin->mobile;
            $employees = [];
            $approval_status = null;
            if($role == "Admin"){
                $employees = Employee::all();
                Log::info("employeeDetails(): Employee Details: ".$employees." admin: ".$admin->company);
            }
            else if($role == "Employer"){
                $approval_status = WorkflowApproval::join("companies","workflow_approval.company","companies.pan")
                                ->where("companies.pan",$admin->company)
                                ->orWhere("companies.group_company_code",$admin->company)
                                ->where("approval_for","Cost Center")
                                ->get(["companies.name as company_name","workflow_approval.approver_email as approver_email"]);
                $company = $admin->company;
                $employees = Employee::join("companies","employees.company","=","companies.pan")
                            ->where("employees.company",$company)
                            ->orWhere("companies.group_company_code",$company)
                            ->where("companies.to_date",null)
                            ->where("employees.to_date",null)
                            ->get(["employees.id","employees.pan_number","employees.company","employees.name","employees.mobile","employees.email","employees.designation"]);
                Log::info("employeeDetails(): Employee Details: ".$employees." for employer admin:".$admin->company);
            }
            return view("/admin/employee/list-employees")->with("employees",$employees)->with("approval_status",$approval_status);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveEmployeeDetails(Request $request){
        $request->validate([
            "uploadFileAdd" => "required|mimes:xlsx,xls",
        ]);
        Excel::import(new EmployeeAddImport, $request->file("uploadFileAdd"));
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        Log::info("saveEmployeeDetails(): Excel Employee add import done by admin: ".$admin->company);
        return redirect("/employee-details");
    }

    public function updateEmployeeDetailsBulk(Request $request){
        $request->validate([
            "uploadFileEdit" => "required|mimes:xlsx,xls",
        ]);
        Excel::import(new EmployeeUpdateImport, $request->file("uploadFileEdit"));
        $admin_id = Session::get("admin_id");
        Log::info("updateEmployeeDetailsBulk(): Excel Employee update import done by admin: ".$admin->id);
        return redirect("/employee-details");
    }

    public function updateEmployeeDetails($id, Request $request){
        $request->validate([
            "email" => "required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",
            "name" => "required|regex:/^[a-zA-Z .]+$/",
            "mobile" => "required|regex:/[6-9]{1}[0-9]{9}/",
            "pan" => "required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}/",
            "designation" => "required"
        ]);
        $email = request("email");
        $name = request("name");
        $mobile = request("mobile");
        $pan = request("pan");
        $designation = request("designation");
        
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        $employee = Employee::where("id",$id)->first();
        $employee->pan_number = $pan;
        $employee->name = $name;
        $employee->mobile = $mobile;
        $employee->email = $email;
        $employee->designation = $designation;
        $employee->updated_by = $admin->email;
        $employee->update();
        $admin_id = Session::get("admin_id");
        Log::info("updateEmployeeDetails(): Update employee details for employee: ".$employee." by admin: ".$admin->company);
        return redirect("/employee-details");
    }

    public function freezeEmployee($id){
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        $employee = Employee::where("id",$id)->first();
        $employee->to_date = Carbon::now()->toDateTimeString();
        $employee->updated_by = $admin->email;
        $employee->update();
        Log::info("freezeEmployee(): Employee: ".$employee." freezed by admin:".$admin->company);
        return redirect("/employee-details");
    }
}