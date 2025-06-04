<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_list extends Model
{
    use HasFactory;
    protected $casts = [
        'payment_transaction_id' => 'string',
    ];
    protected $fillable = [
        'user_uuid',
        'invoice_id',
        'invoice_no',
        'payment_transaction_id',
        'unique_payment_transaction_id',
        'transaction_amount',
        'balance_amount',
        'status',
        'updated_at'
    ];
}
