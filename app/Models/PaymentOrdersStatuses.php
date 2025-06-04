<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrdersStatuses extends Model
{
    use HasFactory;
    protected $fillable = [
        'txn_status',
        'txn_msg',
        'txn_err_msg',
        'clnt_txn_ref',
        'tpsl_bank_cd',
        'tpsl_txn_id',
        'txn_amt',
        'clnt_rqst_meta',
        'tpsl_txn_time',
        'bal_amt',
        'card_id',
        'alias_name',
        'BankTransactionID',
        'mandate_reg_no',
        'token',
        'hash',
        'payment_gatway_response',
        'paymentModeBy',
        'dual_veri_merchantCode',
        'merchantTransactionIdentifier',
        'dual_veri_statusCode',
        'dual_veri_statusMessage',
        'dual_veri_response',
        'pay_res_updatedAt',
        'dual_veri_updatedAt',
    ];
}
