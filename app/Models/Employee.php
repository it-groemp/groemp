<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = "employees";
    protected $fillable = [
        "pan_number",
        "employee_code",
        "name",
        "mobile",
        "email",
        "date_of_birth",
        "designation",
        "company",
        "employee_code",
        "employee_code",
        "employee_code",

    ];
}
