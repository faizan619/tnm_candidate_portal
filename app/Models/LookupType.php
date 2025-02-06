<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupType extends Model
{
    use HasFactory;
	protected $connection = 'mysql_common_database';
     public function lookupValues()
    {
        return $this->hasMany(LookupValue::class, 'type_id');
    }
}
