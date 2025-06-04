<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponserMap extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'standard', 'student_ids', 'status', 'created_by'
    // ];

    protected $primaryKey = 'id';
    protected $guarded = [];
}
