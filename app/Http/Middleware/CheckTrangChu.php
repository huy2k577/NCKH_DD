<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrangChu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {       
            $id= $request->route('id');
            if (!session()->has('lichthi_verified_'. $id)) {
                return redirect()->route('login.get.trangchu');
            }
            return $next($request);
    }
}
