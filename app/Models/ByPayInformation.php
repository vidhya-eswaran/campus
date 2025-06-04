<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ByPayInformation extends Model
{

    use HasFactory;
    protected $table = 'by_pay_informations'; // Specify the table name

    protected $primaryKey = 'id';
    protected $guarded = [];
}
