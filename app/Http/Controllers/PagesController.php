<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Excel;

use App\Models\Category;
use App\Models\Benefit;

class PagesController extends Controller
{
    public function index(){
        $categories = Category::all();
        return view("index")->with("categories",$categories);
    }

    public function ourBenefits(){
        $benefits = Session::get("benefits");
        if($benefits==null){
            $benefits = Benefit::join("categories","benefits.category_id","=","categories.id")->orderBy("categories.name")->orderBy("benefits.name")->get(['benefits.id as id','benefits.name as name','categories.name as category_name','benefits.image_name as image_name']);
            Session::put("benefits",$benefits);
        }        
        return view("company.our-benefits")->with("benefits",$benefits);
    }
    
    public function upload(){
        return view("upload");
    }
}
