<?php

namespace App\Providers;

use Auth;
use App\Models\SitePermission;
use App\Services\Auth\PhpbbUserProvider;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
        if (config("aurora.registerPhpbbAuthProvider") == TRUE) {
            //Register the phpbb Auth Provider
            $this->app['auth']->provider("phpbb", function ($app, array $config) {
                return new PhpbbUserProvider();
            });

            $this->registerPolicies($gate);

            foreach ($this->getPermissions() as $permission) {
                $gate->define($permission->name, function ($user) use ($permission) {
                    return $user->hasRole($permission->roles);
                });

            }
        }

    }

    protected function getPermissions()
    {
        return SitePermission::with('roles')->get();
    }
}
