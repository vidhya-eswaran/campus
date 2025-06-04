<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHistory extends Model
{
    use HasFactory;

  //  protected $table = 'history_student_infos';
    protected $table = 'admitted_students_history';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // protected $fillable = [
    //     'name',
    //     'grade',
    //     'email',
    //     'phone'
    // ];admitted_students_history
}
