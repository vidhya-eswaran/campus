<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwelvethGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'group','group_des'
    ];
}
