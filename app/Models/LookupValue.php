<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookupValue extends Model
{
    use HasFactory;
	protected $connection = 'mysql_common_database';
    public function lookupType()
    {
        return $this->belongsTo(LookupType::class, 'type_id');
    }
}
