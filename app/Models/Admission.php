<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;
    public $table = 'admission_live';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];


}
