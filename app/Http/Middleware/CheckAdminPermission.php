<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{   
  
    public function handle(Request $request, Closure $next, $permission): Response
    {   
        /** @var \App\Models\User $user */
        $user = auth('web')->user();

        if (!$user || !$user->hasPermission($permission)) 
        {
            abort(403, 'Bạn không có quyền truy cập (permission).');
        }

        return $next($request);
    }
}