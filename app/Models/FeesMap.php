<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesMap extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];

    // protected $fillable = [
    //     'standard', 'group', 'amount', 'fees_heading', 'fees_sub_heading', 'date', 'status'
    // ];
}
