<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\EmployeeAvailedBenefits;

use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function saveCartDropdown(Request $request){
        $request->validate([
            "quantity" => "required|numeric",
        ]);

        $employee_id = Session::get("employee");

        $benefit_id = $request->benefit_id;
        $category_id = $request->category_id;
        $name = $request->name;
        $price = $request->price;
        $quantity = $request->quantity;
        $total = $price * $quantity;

        $benefit_amount = Session::get("benefit_amount");
        if($benefit_amount<$total){
            return redirect()->back()->with("error","Please check the balance before purchasing");
        }

        $benefit_amount = $benefit_amount-$total;
        
        $cartBenefit = Cart::where("benefit_id",$benefit_id)->where("price",$price)->first();
        if($cartBenefit==null){
            $cartBenefit = new Cart();
            $cartBenefit->employee_id = $employee_id;
            $cartBenefit->benefit_id = $benefit_id;
            $cartBenefit->category_id = $category_id;
            $cartBenefit->benefit_name = $name;
            $cartBenefit->price = $price;
            $cartBenefit->quantity = $quantity;
            $cartBenefit->save();
        }
        else{
            $cartBenefit->quantity = $quantity;
            $cartBenefit->update();
        }
        Session::put("benefit_amount",$benefit_amount);
        return redirect()->back()->with("success","Benefit is added to the cart");
    }

    public function saveCartNumber(Request $request){
        $request->validate([
            "price" => "required|numeric",
        ]);

        $employee_id = Session::get("employee");

        $benefit_id = $request->benefit_id;
        $category_id = $request->category_id;
        $name = $request->name;
        $price = $request->price;

        $benefit_amount = Session::get("benefit_amount");
        if($benefit_amount<$price){
            return redirect()->back()->with("error","Please check the balance before purchasing");
        }

        $benefit_amount = $benefit_amount-$price;
        
        $cartBenefit = Cart::where("benefit_id",$benefit_id)->where("price",$price)->first();
        if($cartBenefit==null){
            $cartBenefit = new Cart();
            $cartBenefit->employee_id = $employee_id;
            $cartBenefit->benefit_id = $benefit_id;
            $cartBenefit->category_id = $category_id;
            $cartBenefit->benefit_name = $name;
            $cartBenefit->price = $price;
            $cartBenefit->quantity = 0;
            $cartBenefit->save();
        }
        else{
            $cartBenefit->price = $price;
            $cartBenefit->update();
        }
        Session::put("benefit_amount",$benefit_amount);
        return redirect()->back()->with("success","Benefit is added to the cart");
    }

    public function saveCartText(Request $request){
        $request->validate([
            "description" => "required"
        ]);

        $employee_id = Session::get("employee");

        $benefit_id = $request->benefit_id;
        $category_id = $request->category_id;
        $name = $request->name;
        $description = $request->description;

        $cartBenefit = Cart::where("benefit_id",$benefit_id)->first();
        if($cartBenefit==null){
            $cartBenefit = new Cart();
            $cartBenefit->employee_id = $employee_id;
            $cartBenefit->benefit_id = $benefit_id;
            $cartBenefit->category_id = $category_id;
            $cartBenefit->benefit_name = $name;
            $cartBenefit->description = $description;
            $cartBenefit->price = 0;
            $cartBenefit->quantity = 0;
            $cartBenefit->save();
        }
        else{
            $cartBenefit->description = $description;
            $cartBenefit->update();
        }
        return redirect()->back()->with("success","Benefit is added to the cart");
    }

    public function deleteFromCart($id){
        $benefit = Cart::find($id);
        $total = $benefit->price * $benefit->quantity;
        $benefit_amount = Session::get("benefit_amount")+$total;
        Session::put("benefit_amount",$benefit_amount);
        $benefit->delete();
        return redirect()->back();
    }

    public function displayCart(){
        $employee_id = Session::get("employee");
        $cart = Cart::where("employee_id",$employee_id)->get();
        Session::forget("current_cat");
        return view("employee.display-cart")->with("cart",$cart);
    }

    public function saveBenefits(){
        $employee_id = Session::get("employee");
        $employee = Employee::find($employee_id);
        $cart = Cart::where("employee_id",$employee_id)->get();
        $total = 0;
        foreach($cart as $benefit){
            $employee_availed_benefits = new EmployeeAvailedBenefits();
            $employee_availed_benefits->employee_id = $benefit->employee_id;
            $employee_availed_benefits->date_availed = Carbon::now();
            $employee_availed_benefits->benefit_id = $benefit->benefit_id;
            $employee_availed_benefits->category_id = $benefit->category_id;
            $employee_availed_benefits->price = $benefit->price;
            $employee_availed_benefits->quantity = $benefit->quantity;
            $employee_availed_benefits->description = $benefit->description;
            $total = $total + $benefit->price;
            $employee_availed_benefits->save();
            //$benefit->delete();
        }

        $employee_benefits = EmployeeBenefit::where("pan_number",$employee->pan_number)->first();             
        $balance = $employee_benefits->current_benefit - $total;
        $employee_benefits->availed_benefit = $total;
        $employee_benefits->current_benefit = $balance;
        $employee_benefits->update();

        return redirect()->back()->with("success","Benefits saved successfully");
    }
}
