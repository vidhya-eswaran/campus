<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationList extends Model
{
    use HasFactory;
    
    protected $table = 'donation_list';
    
    protected $fillable = [
        'heading', 'image', 'main_description', 'short_description',
        'btn_amt_1', 'btn_amt_2', 'btn_amt_3'
    ];
}
