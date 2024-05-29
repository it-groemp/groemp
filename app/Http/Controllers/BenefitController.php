<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Benefit;

class BenefitController extends Controller
{
    public function benefitDetails(){
        if((new AdminController)->checkAdminSession()){
            $benefits = Benefit::all();
            return view("/admin/benefit/list-benefits")->with("benefits",$benefits);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function addBenefit(){
        if((new AdminController)->checkAdminSession()){
            return view("admin.benefit.add-benefit");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveBenefit(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "amount" => "required|numeric",
                "photo" => "required"
            ]);
            $benefit = new Benefit();
            $benefit->name = request("name");
            $benefit->amount = request("amount");
            $benefit->created_by = "admin"; //need to update after login page
            $benefit->updated_by = "admin"; //need to update after login page
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/benefits/",$fileName);
                $benefit->image_name = $fileName;
            }
            else{
                return redirect()->back()->with("error","Photo couldn't be uploaded");
            }
            $benefit->save();
            return redirect("/benefit-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function editBenefit($id){
        if((new AdminController)->checkAdminSession()){
            $benefit = Benefit::find($id);
            return view("admin.benefit.edit-benefit",["benefit"=>$benefit]);
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function updateBenefit(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "amount" => "required|numeric"
            ]);
            $id=request("id");
            $benefit = Benefit::find($id);
            $benefit->name = request("name");
            $benefit->amount = request("amount");
            $benefit->updated_by = "admin"; //need to update after login page        
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/benefits/",$fileName);
                $benefit->image_name = $fileName;
                $benefit->update();
            }
            else{
                $benefit->update();
            }
            return redirect("/benefit-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function deleteBenefit($id){
        if((new AdminController)->checkAdminSession()){
            $benefit = Benefit::find($id);      
            $benefit->delete();
            return redirect("/benefit-details");
        }
        else{
            return redirect("/admin/login");
        }
    }
}
