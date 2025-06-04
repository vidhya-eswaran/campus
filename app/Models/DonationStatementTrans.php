<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationStatementTrans extends Model
{
    use HasFactory;
    protected $table = 'donation_statement_trans';

    protected $fillable = [
        'donation_id',  
        'donar_id',
        'donar_name',
        'donation_name',
        'amount',
        'transection_id',
        'status',
    ];
}
