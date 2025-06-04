<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageCategoryMaster extends Model
{
    use HasFactory;

    protected $table = 'message_category_master';
    protected $primaryKey = 'id';
    protected $guarded = [];
   
}

