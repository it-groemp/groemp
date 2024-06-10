<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

use App\Models\Category;
use App\Models\Admin;

class CategoryController extends Controller
{
    public function categoryDetails(){
        if((new AdminController)->checkAdminSession()){
            $categories = Category::all();
            Log::info("categoryDetails(): Get categories details. ".$categories);
            return view("/admin/category/list-categories")->with("categories",$categories);
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function addCategory(){
        if((new AdminController)->checkAdminSession()){
            Log::info("addCategory(): Add category details.");
            return view("admin.category.add-category");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function saveCategory(Request $request){        
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "amount" => "nullable|numeric",
                "type" => "required",
                "count" => "nullable|numeric"
            ]);
            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $id=request("id");
            $type = $request->type;
            $values = array();
            $errors = array();
            if($type=="Dropdown"){
                $count = request("count");
                for($i=1;$i<=$count;$i++){
                    $value = request("value".$i);
                    if($value==""){
                        array_push("Value ".$i." cannot be blank");
                    }
                    else{
                        $values[$i-1] = $value;
                    }
                }
            }
            else{
                $values=NULL;
            }
            $category = new Category();
            $category->name = request("name");
            $category->maximum_amount = request("amount");
            $category->type = $type;
            $category->values = $values==NULL? NULL : json_encode($values);
            $category->created_by = $admin->email;
            $category->updated_by = $admin->email;
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/categories/",$fileName);
                $category->image_name = $fileName;
            }
            else{
                Log::error("saveCategory(): Error occurred while saving details: Photo couldn't be uploaded by admin:".$admin->email);
                return redirect()->back()->with("error","Photo couldn't be uploaded");
            }
            Log::info("saveCategory(): Save category: ".$category." Added by ".$admin->email);
            $category->save();
            return redirect("/category-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function editCategory($id){
        if((new AdminController)->checkAdminSession()){
            $category = Category::find($id);
            Log::info("editCategory(): Edit category details for category: ".$category);
            return view("admin.category.edit-category",["category"=>$category]);
        }
        else{
            return redirect("/admin/login");
        }        
    }

    public function updateCategory(Request $request){
        if((new AdminController)->checkAdminSession()){
            $this->validate($request, [
                "name" => "required|max:50",
                "amount" => "nullable|numeric",
                "type" => "required",
                "count" => "nullable|numeric"
            ]);

            $admin_id = Session::get("admin_id");
            $admin = Admin::where("id",$admin_id)->first();
            $id=request("id");
            $type = $request->type;
            $values = array();
            $errors = array();
            if($type=="Dropdown"){
                $count = request("count");
                for($i=1;$i<=$count;$i++){
                    $value = request("value".$i);
                    if($value==""){
                        array_push("Value ".$i." cannot be blank");
                    }
                    else{
                        $values[$i-1] = $value;
                    }
                }
            }
            else{
                $values=NULL;
            }
            
            $category = Category::find($id);
            $category->name = request("name");
            $category->maximum_amount = request("amount");
            $category->type = $type;
            $category->values = $values==NULL? NULL : json_encode($values);
            $category->updated_by = $admin->email;       
            if($files=$request->file("photo")){
                $fileName=$files->getClientOriginalName();  
                $files->move("images/categories/",$fileName);
                $category->image_name = $fileName;
                Log::info("updateCategory(): update category with photo: ".$category." Added by ".$admin->email);
                $category->update();
            }
            else{
                Log::info("updateCategory(): update category: ".$category." Added by ".$admin->email);
                $category->update();
            }
            return redirect("/category-details");
        }
        else{
            return redirect("/admin/login");
        }
    }

    public function deleteCategory($id){
        if((new AdminController)->checkAdminSession()){
            $category = Category::find($id);      
            $category->delete();
            Log::info("deleteCategory(): Delete benefit: ".$category." by ".$admin->company);
            return redirect("/category-details");
        }
        else{
            return redirect("/admin/login");
        }
    }
}
