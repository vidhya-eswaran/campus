<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMarkRecord extends Model
{

    use HasFactory;
 
    protected $primaryKey = 'id';
    protected $guarded = [];
}
