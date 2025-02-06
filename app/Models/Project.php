<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(ClientHo::class);
    }

    public function projectPositions()
    {
        return $this->hasMany(ProjectPosition::class);
    }
    public function projectCastes()
    {
        return $this->hasMany(ProjectCaste::class);
    }
    public function projectEducations()
    {
        return $this->hasMany(ProjectEducation::class);
    }
    public function projectLocations()
    {
        return $this->hasMany(ProjectLocation::class);
    }
    public function projectInstructions()
    {
        return $this->hasMany(ProjectInstruction::class);
    }
    public function projectDocuments()
    {
        return $this->hasMany(ProjectDocument::class);
    }
    public function projectNotifications()
    {
        return $this->hasMany(ProjectNotification::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }
}
