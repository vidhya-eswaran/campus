<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeesMap extends Model
{
    use HasFactory;
    protected $primaryKey = 'slno';
    protected $guarded = [];
}
