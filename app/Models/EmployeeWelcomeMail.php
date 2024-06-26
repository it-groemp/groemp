<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWelcomeMail extends Model
{
    use HasFactory;
    protected $table="employee_welcome_mail";
    protected $fillable = [
        "pan_number"
    ];
}
