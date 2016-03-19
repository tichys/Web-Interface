<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Auth\ForumUserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class SiteRole extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'label', 'description'];
    protected $connection = 'wi';

    public function permissions()
    {
        return $this->belongsToMany(SitePermission::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function get_users($user_models = FALSE)
    {
        $users = DB::connection($this->connection)->table('role_user')->where('role_id', $this->id)->pluck('user_id');

        if (!$user_models) {
            return $users;
        } else {
            $user_data = array();
            foreach ($users as $user) {
                $user_data[] = ForumUserModel::findOrFail($user);
            }
            return Collection::make($user_data);
        }
    }

    public function givePermissionTo(SitePermission $permission)
    {
        if (is_string($permission)) {
            return $this->permissions()->save(
                SitePermission::whereName($permission)->firstOrfail
            );
        }

        return $this->permissions()->save($permission);
    }
}
