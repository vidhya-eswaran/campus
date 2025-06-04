<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpDetail extends Model
{
    use HasFactory;

    protected $table = 'otp_details';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
    ];

    public $timestamps = true;

    // Optionally, add some additional logic
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
