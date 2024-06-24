<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Mail\WelcomeEmployeeMail;

use App\Models\EmployeeWelcomeMail;
use App\Models\WorkflowApproval;
use App\Models\ResetPassword;

class SendEmployeeMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:send-employee-mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Employee welcome mails to those employees whose approvals are confirmed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employee_list = EmployeeWelcomeMail::join("employees","employee_welcome_mail.pan_number","employees.pan_number")
        ->get(["employee_welcome_mail.pan_number", "name", "email"]);

        $company_list = WorkflowApproval::where("approval_for","Employees")->pluck("company");

        if(count($company_list)==0){
            foreach($employee_list as $employee){
                $token = Str::random(20);
                $resetPassword = new ResetPassword();
                $resetPassword->email = $employee->email;
                $resetPassword->token = $token;
                $resetPassword->save();
    
                $link=config("app.url")."/reset-password/$token";
    
                Mail::to($employee->email)->send(new WelcomeEmployeeMail($employee->name,$link));
            
                EmployeeWelcomeMail::where("pan_number",$employee->pan_number)->first()->delete();
            }
        }
        else{
            foreach($employee_list as $employee){
                if(!in_array($employee->company,$company_list)){
                    $token = Str::random(20);
                    $resetPassword = new ResetPassword();
                    $resetPassword->email = $employee->email;
                    $resetPassword->token = $token;
                    $resetPassword->save();
        
                    $link=config("app.url")."/reset-password/$token";        
                    Mail::to($employee->email)->send(new EmployeeWelcomeMail($employee->name,$link));
                
                    EmployeeWelcomeMail::where("pan_number",$employee->pan_number)->first()->delete();
                }
            }
            $this->info("Mail sent");
        }
    }
}
