<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ApprovalController;
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
Route::get("/our-benefits", [PagesController::class,"ourBenefits"])->name("our-benefits");
Route::get("/about-us", [PagesController::class,"aboutUs"])->name("about-us");
Route::get("/contact-us", [PagesController::class,"contactUs"])->name("contact-us");

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
Route::post("/submit-query", [CompanyController::class,"submitQuery"])->name("submit-query");

Route::get("/company-benefit-details", [CompanyController::class,"companyBenefitsDetails"])->name("company-benefit-details");
Route::get("/add-company-benefit", [CompanyController::class,"addCompanyBenefit"])->name("add-company-benefit");
Route::post("/save-company-benefit", [CompanyController::class,"saveCompanyBenefit"])->name("save-company-benefit");
Route::get("/edit-company-benefit/{id}", [CompanyController::class,"editCompanyBenefit"])->name("edit-company-benefit");
Route::post("/update-company-benefit", [CompanyController::class,"updateCompanyBenefit"])->name("update-company-benefit");

Route::get("/approve-cc-details/{token}", [ApprovalController::class, "approveCCDetails"])->name("approve-cc-details");
Route::get("/approve-employee-add-details/{token}", [ApprovalController::class, "approveEmployeeAddDetails"])->name("approve-employee-add-details");
Route::get("/approve-employee-edit-details/{token}", [ApprovalController::class, "approveEmployeeEditDetails"])->name("approve-employee-edit-details");
Route::get("/approve-employee-benefit-add-details/{token}", [ApprovalController::class, "approveEmployeeBenefitAddDetails"])->name("approve-employee-benefit-add-details");
Route::get("/approve-employee-benefit-edit-details/{token}", [ApprovalController::class, "approveEmployeeBenefitEditDetails"])->name("approve-employee-benefit-edit-details");

Route::get("/login", [EmployeeController::class,"login"])->name("login");
Route::get("/employee-login", [EmployeeController::class,"employeeLogin"])->name("employee-login");
Route::get("/logout", [EmployeeController::class,"logout"])->name("logout");
Route::post("/send-otp", [EmployeeController::class,"sendOtp"])->name("send-otp");
Route::post("/verify-otp", [EmployeeController::class,"verifyOtp"])->name("verify-otp");
Route::post("/verify-employee", [EmployeeController::class,"verifyEmployee"])->name("verify-employee");
Route::get("/forgot-password", [EmployeeController::class, "forgotPassword"])->name("forgot-password");
Route::post("/send-password-link", [EmployeeController::class,"sendPasswordLink"])->name("send-password-link");
Route::get("/reset-password/{token}", [EmployeeController::class,"resetPassword"])->name("reset-password");
Route::get("/display-change-password", [EmployeeController::class,"displayChangePassword"])->name("display-change-password");
Route::post("/update-password", [EmployeeController::class,"updatePassword"])->name("update-password");
Route::get("/profile",[EmployeeController::class,"profile"])->name("profile");
Route::get("/employee-benefits-admin",[EmployeeController::class,"employeeBenefitsAdmin"])->name("employee-benefits-admin");
Route::post("/upload-employee-benefits",[EmployeeController::class,"uploadEmployeeBenefits"])->name("upload-employee-benefits");
Route::post("/update-employee-benefits",[EmployeeController::class,"updateEmployeeBenefits"])->name("update-employee-benefits");

Route::get("/admin/login", [AdminController::class,"adminLogin"])->name("admin-login");
Route::get("/admin/logout", [AdminController::class,"adminLogout"])->name("admin-logout");
Route::post("/admin/send-otp", [AdminController::class,"sendAdminOtp"])->name("admin-send-otp");
Route::post("/admin/verify-otp", [AdminController::class,"verifyAdminOtp"])->name("admin-verify-otp");
Route::get("/set-password-admin/{function}", [AdminController::class, "setPassword"])->name("set-password-admin");
Route::post("/send-password-link-admin/{function}", [AdminController::class,"sendPasswordLink"])->name("send-password-link-admin");
Route::get("/reset-password-admin/{token}", [AdminController::class,"resetPassword"])->name("reset-password-admin");
Route::get("/display-change-password-admin", [AdminController::class,"displayChangePassword"])->name("display-change-password-admin");
Route::post("/update-password-admin", [AdminController::class,"updatePassword"])->name("update-password-admin");
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

Route::get("/category-details", [CategoryController::class, "categoryDetails"])->name("category-details");
Route::get("/add-category", [CategoryController::class, "addCategory"])->name("add-category");
Route::post("/save-category", [CategoryController::class, "saveCategory"])->name("save-category");
Route::get("/edit-category/{id}", [CategoryController::class, "editCategory"])->name("edit-category");
Route::post("/update-category", [CategoryController::class, "updateCategory"])->name("update-category");
Route::get("/delete-category/{id}", [CategoryController::class, "deleteCategory"])->name("delete-category");

Route::get("/benefit-details", [BenefitController::class, "benefitDetails"])->name("benefit-details");
Route::get("/add-benefit", [BenefitController::class, "addBenefit"])->name("add-benefit");
Route::post("/save-benefit", [BenefitController::class, "saveBenefit"])->name("save-benefit");
Route::get("/edit-benefit/{id}", [BenefitController::class, "editBenefit"])->name("edit-benefit");
Route::post("/update-benefit", [BenefitController::class, "updateBenefit"])->name("update-benefit");
Route::get("/delete-benefit/{id}", [BenefitController::class, "deleteBenefit"])->name("delete-benefit");

Route::get("/testroute", [PagesController::class, "test"]);
Route::get("/mail", [MailController::class, "sendmail"]);