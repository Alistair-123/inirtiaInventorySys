<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()) {
            Log::info('User not logged in');
            abort(403, 'You do not have access to this page.');
        }

        // Log user information for debugging
        Log::info('User ID: ' . $request->user()->id);
        Log::info('User email: ' . $request->user()->email);
        Log::info('User roles: ' . $request->user()->getRoleNames()->implode(', '));
        Log::info('Checking for role: ' . $role);

        if (!$request->user()->hasRole($role)) {
            Log::info('User does not have the required role: ' . $role);
            abort(403, 'You do not have the required permissions to access this page.');
        }

        Log::info('User has the required role: ' . $role);
        return $next($request);
    }
}
