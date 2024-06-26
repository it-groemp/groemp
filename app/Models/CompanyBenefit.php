<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBenefit extends Model
{
    use HasFactory;
    protected $table = "company_benefits";

    protected $casts = [
        'benefits' => 'array',
    ];
}
