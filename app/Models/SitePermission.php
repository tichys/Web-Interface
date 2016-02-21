<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePermission extends Model
{
    protected $table = 'permissions';

    public function roles()
    {
        return $this->belongsToMany(SiteRole::class,'permission_role','permission_id','role_id');
    }
}
