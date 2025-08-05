<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance  extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'roll_no', 'roll_no');
    }

}
