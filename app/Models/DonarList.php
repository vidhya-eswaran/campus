<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonarList extends Model
{
    use HasFactory;
    protected $table = 'donar_list';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address_1',
        'city_1',
        'state_1',
        'country_1',
        'pincode_1',
        'address_2',
        'city_2',
        'state_2',
        'country_2',
        'pincode_2',
        'pan_aadhar'

    ];
}
