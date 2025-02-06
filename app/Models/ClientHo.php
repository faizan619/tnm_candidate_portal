<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ClientBranch;
use App\Models\ClientContact;

class ClientHo extends Model
{
    use HasFactory;
	protected $connection = 'mysql_common_database';
    public function branches()
    {
        return $this->hasMany(ClientBranch::class, 'client_id');
    }

    public function contacts()
    {
        return $this->hasMany(ClientContact::class, 'client_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'client_id');
    }
}
