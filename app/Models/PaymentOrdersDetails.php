<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrdersDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'internal_txn_id',
        'user_id',
        'amount',
        'name',
        'accNo',
        'custID',
        'paymentMode',
        'mobNo',
        'email',
        'debitStartDate',
        'debitEndDate',
        'maxAmount',
        'amountType',
        'currency',
        'frequency',
        'cardNumber',
        'expMonth',
        'expYear',
        'cvvCode',
        'scheme',
        'accountName',
        'ifscCode',
        'accountType',
        'payment_status',
        'payment_code',
        'order_hash_value',
        'user_return_Url',
        'user_retrun_req_data',
        'user_access_key',
        'updatedat'
    ];

    public function invoice()
    {
        return $this->belongsTo(GenerateInvoiceView::class);
    }
}
