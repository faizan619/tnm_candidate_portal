<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;
    protected $table = 'application_document';
    protected $guarded = [];
    public $timestamps = false;
}
