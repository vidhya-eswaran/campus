<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelAdmission extends Model
{
    use HasFactory;

    protected $table = 'hostel_admissions';
    protected $primaryKey = 'id';
    protected $guarded = [];
   
}

