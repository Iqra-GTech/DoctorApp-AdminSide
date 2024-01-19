<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


     public function handle($request, Closure $next, $permissionName)
     {
         $user = Auth::user();
     
         if (!$user) {
             return redirect('/');
         }
     
         $hasPermission = FacadesDB::table('permission')
             ->where('role_id', $user->role_id)
             ->where('name', $permissionName)
             ->exists();

             
     
         if ($permissionName === 'Create sponsers' && !$hasPermission) {
             session(['noCreateUserPermission' => true]);
         }
     
         if ($hasPermission) {
             return $next($request);
         } else {
             return redirect('/Not-Allowed');
         }
     }
     


}


