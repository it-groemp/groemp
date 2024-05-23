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
            $rmployee = Employee::where("mobile",$mobile)->andWhere("from_date",null)->first()->pluck("id");
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
}
