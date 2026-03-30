<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
  public function handle(Request $request, Closure $next, $role): Response
    {   
             $admin = Auth::user();// lấy user hiện tại

            if (!$admin || !$admin->hasRole($role)) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

            return $next($request); 
    }
}
