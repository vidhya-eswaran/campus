<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionForm extends Model
{
    use HasFactory;
 // public $table = 'admission_live';
 public $table = 'admission_process_live';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [];


}

    // public $table = 'admission_live';
