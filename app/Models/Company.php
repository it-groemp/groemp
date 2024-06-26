<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table="companies";
    protected $fillable=[
        "name",
        "group_company_code",
        "pan",
        "mobile",
        "email",
        "created_by",
        "updated_by"
    ];
}
