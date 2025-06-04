<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{

    use HasFactory;
    protected $table = 'module_permissions'; // Specify the table name

    protected $primaryKey = 'id';
    protected $guarded = [];
    
   public function children()
{
    return $this->hasMany(ModulePermission::class, 'parent_id');
}


}
