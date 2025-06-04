<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements'; // Define actual table name
    protected $primaryKey = 'id';

    protected $fillable = [
        'target_type',
        'target',
        'category',
        'announcementDescription',
        'announcementType',
        'announcementDate',
        'file',
    ];

    protected $casts = [
        'announcementDate' => 'datetime',
    ];

    // Relationship with Category (Assuming a category model exists)
    public function categoryDetails()
    {
        return $this->belongsTo(NotificationCategory::class, 'category');
    }
}
