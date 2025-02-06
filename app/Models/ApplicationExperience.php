<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationExperience extends Model
{
    use HasFactory;
    protected $table = 'application_work';
    protected $guarded = [];
    public $timestamps = false;
}
