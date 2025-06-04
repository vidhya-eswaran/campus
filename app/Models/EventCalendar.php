<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCalendar extends Model
{
    use HasFactory;

    protected $table = 'event_calendars'; // Define actual table name
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'category',
        'isStart',
        'isEnd',
    ];

    protected $casts = [
        'isStart' => 'datetime',
        'isEnd' => 'datetime',
    ];

    // Relationship with EventCategoryMaster
    public function categoryDetails()
    {
        return $this->belongsTo(EventCategoryMaster::class, 'category');
    }
}
