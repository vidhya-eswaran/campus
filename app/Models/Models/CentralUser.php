<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralUser extends Model
{
    protected $connection = 'central';
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'school_id', 'role'];
}
