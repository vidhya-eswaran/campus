<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolMiscellaneousBillMaster extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    // protected $fillable = [
    //     'sub_heading', 'created_by'
    // ];
}
