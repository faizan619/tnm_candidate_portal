<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateCV extends Model
{
    use HasFactory;
    protected $table = 'candidate_cvs';
    public $timestamps = false;
}
