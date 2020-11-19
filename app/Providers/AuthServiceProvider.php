<?php
/**
 * Copyright (c) 2016 'Werner Maisl'
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace App\Providers;

use Auth;
use App\Models\SitePermission;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $gate->define('_heads-of-staff',function($user){
            if($user->byond_linked){
                $serverplayer = $user->serverplayer();
                return $serverplayer->check_whitelist("Heads of Staff");
            }else{
                return FALSE;
            }

        });

        Gate::define('byond_linked', function ($user) {
            return $user->byond_linked;
        });

        foreach($this->getPermissions() as $permission)
        {
            $gate->define($permission->name, function($user) use ($permission)
            {
                return $user->hasRole($permission->roles);
            });
        }
    }

    protected function getPermissions()
    {
        return SitePermission::with('roles')->get();
    }
}
