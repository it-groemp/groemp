<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeFamilyDetails;
use App\Models\Admin;
use App\Models\Company;
use App\Models\ResetPassword;
use App\Models\WorkflowApproval;
use App\Models\Benefit;
use App\Models\Category;
use App\Models\CompanyBenefit;
use App\Models\PasswordBackupEmployee;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Mail\ChangePasswordMail;

use App\Imports\EmployeeAddImport;
use App\Imports\EmployeeBenefitAddImport;
use App\Imports\EmployeeBenefitUpdateImport;
use Excel;

class EmployeeController extends Controller
{
    public function checkSession(){
        if(Session::get("employee")){
            return true;
        }
        else{
            return false;
        }
    }

    public function login(){
        return view("login");
    }
    
    public function employeeLogin(){
        return view("employee.login");
    }

    public function logout(){
        Session::flush();
        return redirect("/");
    }

    public function verifyEmployee(Request $request){
        $request->validate([
            "pan" => "required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/",
            "password" => "required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/"
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
        return redirect()->back()->withErrors(["errors"=>$error]);
    }

    public function forgotPassword(){
        return view("employee.forgot-password");
    }

    public function sendPasswordLink(Request $request){
        $request->validate([
            "pan" => "required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/"
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
            
            $char = Str::position($email, "@");
            return redirect()->back()->with("success","Reset password link has been sent to the email id: ".Str::mask($email, '*', 3, $char-3));
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
            "password" => "required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/",
            "cnfm_password" => "required|regex:/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/"
        ]);

        $pan = Session::get("pan");
        $password = $request->password;
        $cnfm_password = $request->cnfm_password;

        if($password!=$cnfm_password){
            Log::error("updatePassword(): Error occurred while updating password for ".$pan." Error: Both the passwords do not match");
            return redirect()->back()->withErrors(["errors"=>"Both the passwords do not match"]);
        }
        else{
            $password_list = PasswordBackupEmployee::where("pan",$pan)->pluck("password")->toArray();
            $hash_password = password_hash($password,PASSWORD_DEFAULT);

            foreach($password_list as $hash_password){
                if(password_verify($password,$hash_password)){
                    Log::error("updatePassword(): Error occurred while updating password for PAN: ".$pan." Error: Please do not enter last 3 passwords");
                    return redirect()->back()->withErrors(["errors"=>"Please do not enter last 3 passwords"]);
                }                
            }
            
            $password_backup = new PasswordBackupEmployee();

            if(count($password_list)==3){
                $backup = PasswordBackupEmployee::where("pan",$pan)->first();
                $backup->delete();
            }

            $password_backup->pan = $pan;
            $password_backup->password = password_hash($password,PASSWORD_DEFAULT);
            $password_backup->save();

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
        if($this->checkSession()){
            $id = Session::get("employee");
            $employee = Employee::find($id);
            $photo = $employee->photo;
            if(is_null($photo) || empty($photo)){
                $employee->photo = strtoupper($employee->name[0]).".png";
                $photo=null;
            }
            $company = Company::where("pan",$employee->company)->first()->name;
            $family = EmployeeFamilyDetails::where("pan_number",$employee->pan_number)->orderBy("relation","desc")->orderBy("id")->get()->toArray();
            return view("employee.profile")
                    ->with("employee",$employee)
                    ->with("photo",$photo)
                    ->with("company",$company)
                    ->with("family",$family);
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
            "uploadAddFile" => "required|mimes:xlsx,xls",
        ]);
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        Excel::import(new EmployeeBenefitAddImport, $request->file("uploadAddFile"));
        Log::info("uploadEmployeeBenefits(): Employee Benefits added for employees by admin: ".$admin->email);
        return redirect("/employee-benefits-admin");
    }

    public function updateEmployeeBenefits(Request $request){
        $request->validate([
            "uploadEditFile" => "required|mimes:xlsx,xls",
        ]);
        $admin_id = Session::get("admin_id");
        $admin = Admin::where("id",$admin_id)->first();
        Excel::import(new EmployeeBenefitUpdateImport, $request->file("uploadEditFile"));
        Log::info("updateEmployeeBenefits(): Employee Benefits updated for employees by admin: ".$admin->email);
        return redirect("/employee-benefits-admin");
    }

    public function savePersonal(Request $request){
        if($this->checkSession()){
            
        }
        else{
            return redirect("/employee-login");
        }
        $request->validate([
            "name" => "required|regex:/^[a-zA-Z .]+$/",
            "mobile"=> "required|regex:/[6-9]{1}[0-9]{9}/",
            "dob"=> "required"
        ]);

        $name = request("name");
        $mobile = request("mobile");
        $dob = request("dob");
        $id = Session::get("employee");
        $employee = Employee::find($id);
        $employee->name = $name;
        $employee->mobile = $mobile;
        $employee->date_of_birth = $dob;
        $employee->updated_by = $employee->email;
        $employee->update();

        Log::info("savePersonal(): Employee data saved for id: ".$id);
        return redirect()->back();
    }

    public function saveMarital(Request $request){
        if($this->checkSession()){
            $request->validate([
                "marital_status" => "required",
            ]);
            $marital_status = $request->marital_status;
            $id = Session::get("employee");
            $employee = Employee::find($id);
            $employee->marital_status = $marital_status;
            $employee->updated_by = $employee->email;
            $employee->update();
    
            Log::info("saveMarital(): Marital status updated for employee: ".$id);
    
            $employee_family = EmployeeFamilyDetails::where("pan_number",$employee->pan_number)->where("relation","Spouse")->first();
    
            if($marital_status=="Married"){
                $request->validate([
                    "spouse_name" => "required|regex:/^[a-zA-Z .]+$/",
                    "spouse_dob"=> "required"
                ]);
                $spouse_name = $request->spouse_name;
                $spouse_dob = $request->spouse_dob;
                if($employee_family==null){
                    $employee_family = new EmployeeFamilyDetails();
                    $employee_family->pan_number = $employee->pan_number;
                    $employee_family->relation = "Spouse";
                    $employee_family->name = $spouse_name;
                    $employee_family->date_of_birth = $spouse_dob;
                    $employee_family->created_by = $employee->email;
                    $employee_family->updated_by = $employee->email;
                    $employee_family->save();
                    Log::info("saveMarital(): Save spouse data");
                }
                else{
                    $employee_family->name = $spouse_name;
                    $employee_family->date_of_birth = $spouse_dob;
                    $employee_family->updated_by = $employee->email;
                    $employee_family->update();
                    Log::info("saveMarital(): Updated spouse data");
                }
            }
            else{
                $employee_family->delete();
                Log::info("saveMarital(): Deleted spouse data");
            }
            return redirect()->back();
        }
        else{
            return redirect("/employee-login");
        }
    }

    public function saveKids(Request $request){
        if($this->checkSession()){
            $request->validate([
                "num_of_kids" => "required",
            ]);
            $num_of_kids = $request->num_of_kids;
    
            $id = Session::get("employee");
            $employee = Employee::find($id);
            $employee->num_of_kids = $num_of_kids;
            $employee->updated_by = $employee->email;
            $employee->update();
    
            $employee_family = EmployeeFamilyDetails::where("pan_number",$employee->pan_number)->where("relation","Kid")->get();
            foreach($employee_family as $family){
                $family->delete();
            }
            Log::info("saveKids(): Deleted all the kids info for employee: ".$id);
    
            if($num_of_kids>0){
                $request->validate([
                    "kid1_name" => "required|regex:/^[a-zA-Z .]+$/",
                    "kid1_dob"=> "required"
                ]);
    
                $employee_family1 = new EmployeeFamilyDetails();
                $employee_family1->pan_number = $employee->pan_number;
                $employee_family1->name = $request->kid1_name;
                $employee_family1->relation = "Kid";
                $employee_family1->date_of_birth = $request->kid1_dob;
                $employee_family1->created_by = $employee->email;
                $employee_family1->updated_by = $employee->email;
                $employee_family1->save();
    
                Log::info("saveKids(): Kid1 info saved for employee: ".$id);
    
                if($num_of_kids>1){
                    $request->validate([
                        "kid2_name" => "required|regex:/^[a-zA-Z .]+$/",
                        "kid2_dob"=> "required"
                    ]);
                    $employee_family2 = new EmployeeFamilyDetails();
                    $employee_family2->pan_number = $employee->pan_number;
                    $employee_family2->name = $request->kid2_name;
                    $employee_family2->relation = "Kid";
                    $employee_family2->date_of_birth = $request->kid2_dob;
                    $employee_family2->created_by = $employee->email;
                    $employee_family2->updated_by = $employee->email;
                    $employee_family2->save();
        
                    Log::info("saveKids(): Kid2 info saved for employee: ".$id);
    
                    if($num_of_kids>2){
                        $request->validate([
                            "kid3_name" => "required|regex:/^[a-zA-Z .]+$/",
                            "kid3_dob"=> "required"
                        ]);
                        $employee_family3 = new EmployeeFamilyDetails();
                        $employee_family3->pan_number = $employee->pan_number;
                        $employee_family3->name = $request->kid3_name;
                        $employee_family3->relation = "Kid";
                        $employee_family3->date_of_birth = $request->kid3_dob;
                        $employee_family3->created_by = $employee->email;
                        $employee_family3->updated_by = $employee->email;
                        $employee_family3->save();
            
                        Log::info("saveKids(): Kid3 info saved for employee: ".$id);
                    }
                }
            }
            return redirect()->back();
        }
        else{
            return redirect("/employee-login");
        }
    }

    public function changePhoto(Request $request){
        if($this->checkSession()){
            $id=Session::get("employee");
            $request->validate([
                "employee-photo"=> "required"
            ]);
            $photo=$request->file("employee-photo");
            $name=$photo->getClientOriginalName(); 
            $photo->move("images/employee-images",$name); 
            $employee = Employee::find($id);
            $employee->photo=$name;
            $employee->update();

            Log::info("changePhoto(): Photo updated for employee: ".$id);
            return redirect()->back();
        }
        else{
            return redirect("/employee-login");
        }
    }

    public function deletePhoto(){
        if($this->checkSession()){
            $id=Session::get("employee");
            $employee = Employee::find($id);
            $photo = $employee->photo;
            File::delete("images/employee-images/".$photo);
            $employee->photo=null;
            $employee->update();
            Log::info("deletePhoto(): Photo deleted for employee: ".$id);
            return redirect()->back();
        }
        else{
            return redirect("/employee-login");
        }
    }

    public function employeeBenefitsHome(){
        if($this->checkSession()){         
            $id=Session::get("employee");
            $employee = Employee::find($id);
            $employee_benefit = EmployeeBenefit::where("pan_number",$employee->pan_number)->first();
            $company = $employee->company;
            $company_benefits_list = CompanyBenefit::where("company",$company)->pluck("benefits");
            $benefits_array = json_decode($company_benefits_list[0]);
            $benefits_list = Benefit::whereIn("id",$benefits_array)->get()->sortBy("category_id");
            $categories = $benefits_list->pluck("category_id")->unique();
            $category_list = Category::whereIn("id",$categories)->get();
            Session::put("category_list",$category_list);
            Session::put("benefits_list",$benefits_array);
            Session::put("benefit_amount",($employee_benefit->current_benefit+$employee_benefit->previous_balance));
            Session::forget("current_cat");
            return view("employee.employee-benefits-home");
        }
        else{
            return redirect("/employee-login");
        }
    }

    public function employeeBenefits($id){
        $benefits_array = Session::get("benefits_list");
        $benefits_category = Benefit::where("category_id",$id)->whereIn("id",$benefits_array)->get();
        $category = Category::find($id);
        Session::put("current_cat",$id);
        return view("employee.employee-benefits")->with("benefits_list",$benefits_category)->with("category",$category);
    }
}
