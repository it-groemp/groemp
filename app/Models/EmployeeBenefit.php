<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBenefit extends Model
{
    use HasFactory;
    protected $table="employee_benefits";
    protected $fillable = [
        "pan_number",
        "company",
        "month",
        "current_benefit"
    ];
}
