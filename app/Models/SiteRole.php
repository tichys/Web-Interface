<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteRole extends Model
{
    protected $table = 'roles';

    public function permissions()
    {
        return $this->belongsToMany(SitePermission::class,'permission_role','role_id','permission_id');
    }

    public function givePermissionTo(SitePermission $permission)
    {
        if(is_string($permission))
        {
            return $this->permissions()->save(
                SitePermission::whereName($permission)->firstOrfail
            );
        }

        return $this->permissions()->save($permission);
    }
}
