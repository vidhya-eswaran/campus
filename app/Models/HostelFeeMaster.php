<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelFeeMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_heading', 'created_by'
    ];
}
