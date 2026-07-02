<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect based on what they are trying to access
            if ($request->is('admin*')) {
                return redirect()->route('home')->with('error', 'Unauthorized access.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
