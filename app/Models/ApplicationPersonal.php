<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPersonal extends Model
{
    use HasFactory;
    protected $table = 'application_personal';
    protected $guarded = [];

    public function requirement()
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }
}
