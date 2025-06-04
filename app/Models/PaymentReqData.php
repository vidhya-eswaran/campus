<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReqData extends Model
{
    use HasFactory;

    protected $table = 'payment_req_data';
    protected $primaryKey = 'payment_req_id';
    protected $guarded = [];
    
}
