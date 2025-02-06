<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationCertification extends Model
{
    use HasFactory;
    protected $table = 'application_certification';
    protected $guarded = [];
    public $timestamps = false;
}
