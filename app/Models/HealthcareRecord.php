<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcareRecord extends Model
{
    use HasFactory;

    protected $table = 'healthcare_records';
    protected $primaryKey = 'id';
    protected $guarded = [];
   
}

