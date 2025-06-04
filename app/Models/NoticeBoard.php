<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    use HasFactory;

    protected $table = 'notice_boards'; // Define actual table name
    protected $primaryKey = 'id';
    protected $guarded = [];
    public function categoryDetails()
    {
        return $this->belongsTo(NotificationCategory::class, 'category');
    }
}
