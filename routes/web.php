<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", [PagesController::class,"index"])->name("home");
Route::get("/our-brands", [PagesController::class,"ourBrands"])->name("our-brands");

Route::get("/company-details", [CompanyController::class,"companyDetails"])->name("company-details");
Route::get("/register-company", [CompanyController::class,"registerCompany"])->name("register-company");
Route::post("/save-company-details", [CompanyController::class,"saveCompanyDetails"])->name("save-company-details");

Route::get("/login", [EmployeeController::class,"login"])->name("login");
Route::get("/logout", [EmployeeController::class,"logout"])->name("logout");
Route::post("/send-otp", [EmployeeController::class,"sendOtp"])->name("send-otp");
Route::post("/verify-otp", [EmployeeController::class,"verifyOtp"])->name("verify-otp");
Route::get("/profile",[EmployeeController::class,"profile"])->name("profile");

Route::get("/admin/login", [EmployeeController::class,"adminLogin"])->name("admin-login");
Route::get("/admin/logout", [EmployeeController::class,"adminLogout"])->name("admin-logout");
Route::post("/send-admin-otp", [EmployeeController::class,"sendAdminOtp"])->name("send-admin-otp");
Route::post("/verify-admin-otp", [EmployeeController::class,"verifyAdminOtp"])->name("verify-admin-otp");

Route::get("/employee-details", [EmployeeController::class,"employeeDetails"])->name("employee-details");
Route::post("/save-employee-details", [EmployeeController::class,"saveEmployeeDetails"])->name("save-employee-details");

Route::get("/upload", [PagesController::class, "upload"])->name("upload");
Route::post("/save", [PagesController::class, "save"])->name("save");

Route::get("/benefit-details", [BenefitController::class, "benefitDetails"])->name("benefit-details");
Route::get("/add-benefit", [BenefitController::class, "addBenefit"])->name("add-benefit");
Route::post("/save-benefit", [BenefitController::class, "saveBenefit"])->name("save-benefit");
Route::get("/edit-benefit/{id}", [BenefitController::class, "editBenefit"])->name("edit-benefit");
Route::post("/update-benefit", [BenefitController::class, "updateBenefit"])->name("update-benefit");
Route::get("/delete-benefit/{id}", [BenefitController::class, "deleteBenefit"])->name("delete-benefit");

Route::get("/brand-details", [BrandController::class, "brandDetails"])->name("brand-details");
Route::get("/add-brand", [BrandController::class, "addBrand"])->name("add-brand");
Route::post("/save-brand", [BrandController::class, "saveBrand"])->name("save-brand");
Route::get("/edit-brand/{id}", [BrandController::class, "editBrand"])->name("edit-brand");
Route::post("/update-brand", [BrandController::class, "updateBrand"])->name("update-brand");
Route::get("/delete-brand/{id}", [BrandController::class, "deleteBrand"])->name("delete-brand");