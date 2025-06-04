<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GenerateInvoiceView extends Model
{
    use HasFactory;
    protected $primaryKey = 'slno';
    protected $guarded = [];

public function byPayInformations()
{
    return $this->hasMany(ByPayInformation::class, 'invoice_id', 'slno');
            //    ->whereNull('inv_amt'); // Add this condition to filter by null inv_amt
}


    public function sponsors()
    {
        return $this->belongsTo(User::class, 'sponser_id', 'id');
    }
    
    function getMostRecentDues($student_id,$feecat) {
    $record = DB::table('by_pay_informations')
                ->where('student_id', $student_id)
                ->where('type', $feecat)
                ->orderBy('created_at', 'desc')
                ->first();
    return $record ? $record->due_amount : 0;
}

/**
 * Get the most recent excess for a student.
 *
 * @param int $student_id
 * @return float
 */
function getMostRecentExcess($student_id,$feecat) {
    $record = DB::table('by_pay_informations')
                ->where('student_id', $student_id)
                ->where('type', $feecat)
                ->orderBy('created_at', 'desc')
                ->first();

    // Check if record exists and return the appropriate excess amount
    if ($record) {
        return ($feecat === 'school') ? $record->s_excess_amount : $record->h_excess_amount;
    }

    return 0; // Return 0 if no record found
}

    
}
