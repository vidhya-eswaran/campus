<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategoryMaster extends Model
{
    use HasFactory;

    protected $table = 'event_category_masters'; // Define actual table name
    protected $primaryKey = 'id';
    protected $guarded = [];
}
