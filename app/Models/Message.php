<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
        use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];


public function attachments()
{
    return $this->hasMany(MessageAttachment::class);
}

public function replies()
{
    return $this->hasMany(Message::class, 'about');
}

public function parent()
{
    return $this->belongsTo(Message::class, 'about');
}

public function user()
{
    return $this->belongsTo(User::class);
}


}

