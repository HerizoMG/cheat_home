<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $userRoles = json_decode($request->user()->roles);
        if (count($userRoles) == count($roles)){
            $tmp = false;
            foreach ($roles as $role){
                if (in_array($role,$userRoles)){
                    $tmp = true;
                }else{
                    $tmp = false;
                }
            }
            if ($tmp){
                return $next($request);
            }
        }
        abort(401, 'Role Unauthorized');
    }
}
