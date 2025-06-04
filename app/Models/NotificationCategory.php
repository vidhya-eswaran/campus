<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    use HasFactory;

    protected $table = 'notification_categories'; // Define your actual table name
    protected $primaryKey = 'id';
    protected $guarded = [];
}

