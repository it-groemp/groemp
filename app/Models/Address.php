<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = "address";
    protected $fillable=[
        "company",
        "gst",
        "state",
        "city",
        "pincode",
        "created_by",
        "updated_by"
    ];
}
