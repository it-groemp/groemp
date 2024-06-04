<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Benefit;
use App\Models\Admin;

class BenefitController extends Controller
{
    public function benefitDetails(){
        if((new AdminController)->checkAdminSession()){
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")->get(['benefits.id as id','benefits.name as name','categories.name as category_name','benefits.image_name as image_name']);
            return view("admin.benefits.list-benefits")->with("benefits",$benefits);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function addBenefit(){
        if((new AdminController)->checkAdminSession()){
            $categories = Category::all();
            return view("admin.benefits.add-benefit")->with("categories",$categories);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveBenefit(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "category" => "required",
                "photo" => "required"
            ]);
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $benefit = new Benefit();
            $benefit->name = request("name");
            $benefit->category_id = request("category");
            $benefit->created_by = $admin->email;
            $benefit->updated_by = $admin->email;
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
            $categories = Category::all();
            return view("admin.benefits.edit-benefit",["benefit"=>$benefit, "categories"=>$categories]);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function updateBenefit(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "category" => "required"
            ]);
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $id=request("id");
            $benefit = Benefit::find($id);
            $benefit->name = request("name");
            $benefit->category_id = request("category");
            $benefit->updated_by = $admin->email;       
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
