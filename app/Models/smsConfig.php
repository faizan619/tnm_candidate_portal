<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class smsConfig extends Model
{
    use HasFactory;
	protected $connection = 'mysql_common_database';
     public $timestamps = false;

}
