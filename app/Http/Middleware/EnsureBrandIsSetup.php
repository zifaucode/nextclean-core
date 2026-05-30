<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBrandIsSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin-outlet')) {
            if (empty($user->brand_name) || empty($user->brand_logo)) {
                if (!$request->routeIs('admin.setup.*') && !$request->routeIs('logout')) {
                    return redirect()->route('admin.setup.brand');
                }
            }
        }
        return $next($request);
    }
}
