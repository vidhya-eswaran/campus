<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifecycleLog extends Model
{
    protected $fillable = [
        'heading', 'student_id', 'event_type', 'extra', 'logged_at'
    ];

    public $timestamps = true;
}
