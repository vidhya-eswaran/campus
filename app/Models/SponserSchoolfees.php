<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponserSchoolfees extends Model
{
    use HasFactory;
    protected $primaryKey = 'slno';
    protected $fillable = [
        'student_id',
        'sponser_id',
        'date',
        'fee_heading',
        'fee_sub_heading',
        'amount',
        'due',
        'pay_type_id',
        'payment_status',
        'note'
    ];
}
