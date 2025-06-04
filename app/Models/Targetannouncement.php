<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Targetannouncement extends Model
{
    use HasFactory;

    protected $table = 'target_announcements'; // Your actual table name
    protected $primaryKey = 'id';
    protected $fillable = ['target_audience', 'target_group', 'user_details', 'delete_status'];

    protected $casts = [
        'target_group' => 'array',
        'user_details' => 'array',
    ];
}
