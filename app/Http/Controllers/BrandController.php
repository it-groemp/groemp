<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Benefit;

class BrandController extends Controller
{
    public function brandDetails(){
        if((new AdminController)->checkAdminSession()){
            $brand = Brand::join("benefits","brands.benefit_id","=","benefits.id")->get(['brands.id as id','brands.name as name','benefits.name as benefit_name','brands.image_name as image_name']);
            return view("admin.brands.list-brand")->with("brands",$brand);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function addbrand(){
        if((new AdminController)->checkAdminSession()){
            $benefits = Benefit::all()->pluck("name")->toArray();
            return view("admin.brands.add-brand")->with("benefits",$benefits);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function savebrand(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "benefit" => "required",
                "photo" => "required"
            ]);
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $brand = new Brand();
            $brand->name = request("name");
            $brand->benefit_name = request("benefit");            
            $brand->created_by = $admin->email;
            $brand->updated_by = $admin->email;
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/brands/",$fileName);
                $brand->image_name = $fileName;
            }
            else{
                return redirect()->back()->with("error","Photo couldn't be uploaded");
            }
            $brand->save();
            return redirect("/brand-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function editBrand($id){
        if((new AdminController)->checkAdminSession()){
            $brand = Brand::find($id);
            $benefits = Benefit::all()->pluck("name")->toArray();
            return view("admin.brands.edit-brand",["brand"=>$brand, "benefits"=>$benefits]);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function updateBrand(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "benefit" => "required"
            ]);
            $id = Session::get("admin_id");
            $admin = Admin::where("id",$id)->first();
            $id=request("id");
            $brand = Brand::find($id);
            $brand->name = request("name");
            $brand->benefit_name = request("benefit");
            $benefit->updated_by = $admin->email;       
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/brands/",$fileName);
                $brand->image_name = $fileName;
                $brand->update();
            }
            else{
                $brand->update();
            }
            return redirect("/brand-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function deleteBrand($id){
        if((new AdminController)->checkAdminSession()){
            $brand = Brand::find($id);      
            $brand->delete();
            return redirect("/brand-details");
        }
        else{
            return redirect("/admin/login");
        }
    }
}
