<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentNotificationData extends Model
{
    use HasFactory;

    protected $table = 'payment_notification_datas';
    protected $primaryKey = 'id';
    protected $guarded = [];
}