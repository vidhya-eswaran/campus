<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
      protected $casts = [
        'class_teacher' => 'array',
        'std_and_sub_details' => 'array',
    ];

}
