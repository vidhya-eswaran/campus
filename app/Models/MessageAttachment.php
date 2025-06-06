<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MessageAttachment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
