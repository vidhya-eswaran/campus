<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonarList extends Model
{
    use HasFactory;
    protected $table = 'donar_list';

    protected $fillable = [
        'donor_name',
        'email',
        'mobile_no',
        'address_line_1',
        'city',
        'state',
        'country',
        'pincode',
        'address_line_2',
        'city_2',
        'state_2',
        'country_2',
        'pincode_2',
        'pan_no',
        'typeOfDonation',
        'mode_of_payment',
        'payment_type',
        'check_dd_trans_id',

    ];
}
