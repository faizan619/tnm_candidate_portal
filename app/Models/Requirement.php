<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;
    public function requirementQualification()
    {
        return $this->hasMany(RequirementQualification::class);
    }
    public function requirementAgeLimit()
    {
        return $this->hasMany(RequirementAgeLimit::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
