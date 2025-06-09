<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMarkRecord extends Model
{

    use HasFactory;
    protected $table = 'student_mark_records';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
