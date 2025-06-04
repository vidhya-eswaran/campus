<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $casts = [
        'fromDate' => 'date',
        'toDate' => 'date',
    ];
}