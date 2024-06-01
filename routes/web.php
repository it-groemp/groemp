<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MailController;
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

Route::get("/company-details-admin", [CompanyController::class,"companyDetailsAdmin"])->name("company-details-admin");
Route::get("/company-details-employer", [CompanyController::class,"companyDetailsEmployer"])->name("company-details-employer");
Route::get("/register-company", [CompanyController::class,"registerCompany"])->name("register-company");
Route::post("/save-company-details", [CompanyController::class,"saveCompanyDetails"])->name("save-company-details");
Route::get("/cc-details", [CompanyController::class,"ccDetails"])->name("cc-details");
Route::post("/save-cc-details", [CompanyController::class,"saveCCDetails"])->name("save-cc-details");
Route::post("/update-cc-details/{id}", [CompanyController::class,"updateCCDetails"])->name("update-cc-details");
Route::get("/workflow-details", [CompanyController::class,"workflowDetails"])->name("workflow-details");
Route::post("/save-workflow", [CompanyController::class,"saveWorkflow"])->name("save-workflow");
Route::post("/update-workflow/{id}", [CompanyController::class,"updateWorkflow"])->name("update-workflow");

Route::get("/login", [EmployeeController::class,"login"])->name("login");
Route::get("/logout", [EmployeeController::class,"logout"])->name("logout");
Route::post("/send-otp", [EmployeeController::class,"sendOtp"])->name("send-otp");
Route::post("/verify-otp", [EmployeeController::class,"verifyOtp"])->name("verify-otp");
Route::get("/profile",[EmployeeController::class,"profile"])->name("profile");

Route::get("/admin/login", [AdminController::class,"adminLogin"])->name("admin-login");
Route::get("/admin/logout", [AdminController::class,"adminLogout"])->name("admin-logout");
Route::get("/set-password-admin/{function}", [AdminController::class, "setPassword"])->name("set-password-admin");
Route::post("/send-password-link/{function}", [AdminController::class,"sendPasswordLink"])->name("send-password-link");
Route::get("/reset-password/{token}", [AdminController::class,"resetPassword"])->name("reset-password");
Route::get("/display-change-password", [AdminController::class,"displayChangePassword"])->name("display-change-password");
Route::post("/update-password", [AdminController::class,"updatePassword"])->name("update-password");

Route::post("/admin/verify-admin", [AdminController::class,"verifyAdmin"])->name("verify-admin");
Route::get("/admin/display-admin", [AdminController::class,"displayAdmin"])->name("display-admin");
Route::get("/admin/add-admin", [AdminController::class,"addAdmin"])->name("add-admin");
Route::post("/admin/save-admin", [AdminController::class,"saveAdmin"])->name("save-admin");
Route::get("/employee-details", [AdminController::class,"employeeDetails"])->name("employee-details");
Route::post("/save-employee-details", [AdminController::class,"saveEmployeeDetails"])->name("save-employee-details");
Route::post("/update-employee-details/{id}", [AdminController::class,"updateEmployeeDetails"])->name("update-employee-details");
Route::post("/update-employee-details-bulk", [AdminController::class,"updateEmployeeDetailsBulk"])->name("update-employee-details-bulk");
Route::get("/freeze-employee/{id}", [AdminController::class,"freezeEmployee"])->name("freeze-employee");

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

Route::get("/testroute", [MailController::class, "sendmail"]);