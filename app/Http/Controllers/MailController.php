<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeWelcomeMail;

class MailController extends Controller
{
    public function sendmail(){
        //Mail::to("it.groemp@gmail.com")->send(new SetPasswordAdminMail("Karishma","https://groemp.com"));
        $link=config("app.url")."/reset-password/shfntkDtbshsf";
        Mail::to("it.groemp@gmail.com")->send(new EmployeeWelcomeMail("Karishma",$link));
    }
}