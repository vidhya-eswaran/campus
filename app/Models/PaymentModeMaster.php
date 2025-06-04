<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModeMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'paymenttype', 'created_by'
    ];
}
