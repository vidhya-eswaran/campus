<?php
// app/Models/Webinar.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    protected $fillable = [
        'staff_id',
        'class',
        'section',
        'teacher_name',
        'host_name',
        'date',
        'start_time',
        'end_time',
        'description'
    ];
}
