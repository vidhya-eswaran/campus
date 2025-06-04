<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionMaster extends Model
{
    use HasFactory;

    protected $table = 'section_masters'; // Define your actual table name
    protected $primaryKey = 'id';
    protected $guarded = [];
}

