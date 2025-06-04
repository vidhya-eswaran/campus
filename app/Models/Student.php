<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

  //  protected $table = 'student_infos';
    protected $table = 'admitted_students';
    protected $primaryKey = 'id';
    protected $guarded = [];

    // protected $fillable = [
    //     'name',
    //     'grade',
    //     'email',
    //     'phone'
    // ];
}
