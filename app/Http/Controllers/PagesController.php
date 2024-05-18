<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Excel;

use App\Imports\BenefitImport;
use App\Imports\BrandImport;

use App\Models\Benefit;
use App\Models\Brand;

class PagesController extends Controller
{
    public function index(){
        $benefits = Benefit::all();
        return view("index")->with("benefits",$benefits);
    }

    public function ourBrands(){
        $brands = Session::get("brands");
        if($brands==null){
            $brands = Brand::join("benefits","brands.benefit_id","=","benefits.id")->orderBy("benefits.name")->orderBy("brands.name")->get(['brands.id as id','brands.name as name','benefits.name as benefit_name','brands.image_name as image_name']);
            Session::put("brands",$brands);
        }        
        return view("company.our-brands")->with("brands",$brands);
    }
    
    public function upload(){
        return view("upload");
    }

    public function save(Request $request){
        Excel::import(new BenefitImport, $request->file("uploadFile"));
        //Excel::import(new BrandImport, $request->file("uploadFile"));
    }
}
