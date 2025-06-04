<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffFeeMasters extends Model
{
    use HasFactory;

    protected $table = 'staff_fee_masters';
    protected $primaryKey = 'id';
    protected $guarded = [];
   
}

