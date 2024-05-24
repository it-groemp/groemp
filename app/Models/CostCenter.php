<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $table="cost_centers";
    protected $fillable = [
        "company",
        "cc1",
        "cc2",
        "cc3",
        "cc4",
        "cc5",
        "cc6",
        "cc7",
        "cc8",
        "cc9",
        "cc10",
    ];
}
