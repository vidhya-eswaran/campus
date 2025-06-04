<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'options', 'delete_status'];

    // Ensure options field is stored as JSON and retrieved as an array
    protected $casts = [
        'options' => 'array',
    ];
}
