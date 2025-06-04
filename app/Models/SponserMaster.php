<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponserMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'occupation', 'company_name', 'location', 'email_id', 'phone', 'address1', 'address2', 'city', 'state', 'pincode', 'status', 'created_by','gst','pan','user_id'
    ];
}
