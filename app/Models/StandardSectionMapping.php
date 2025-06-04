<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardSectionMapping extends Model
{
    use HasFactory;

    protected $table = 'standard_section_mappings';
    protected $primaryKey = 'id';
    protected $fillable = ['standard', 'sections', 'group', 'delete_status'];

    protected $casts = [
        'sections' => 'array', // Convert JSON to array automatically
        'group' => 'string',   // Keep group as string (optional)
    ];
}
