<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationEducation extends Model
{
    use HasFactory;
    protected $table = 'application_education';
    protected $guarded = [];
    public $timestamps = false;
}
