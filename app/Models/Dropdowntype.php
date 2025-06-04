<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropdowntype extends Model
{
    use HasFactory;

    protected $table = 'dropdown_types'; // Define your actual table name
    protected $primaryKey = 'id';
    protected $guarded = [];
}

