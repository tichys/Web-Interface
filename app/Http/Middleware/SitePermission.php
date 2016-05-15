<?php

namespace App\Http\Middleware;

use Closure;

class SitePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if ($request->user()->can($permission)) {
            return $next($request);
        }

        abort('403', 'You do not have the required permission');
    }
}
