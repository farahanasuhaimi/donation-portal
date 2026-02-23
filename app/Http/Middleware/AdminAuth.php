<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = (string) $request->session()->get('auth_role');

        if ($request->session()->get('admin_logged_in') && in_array($role, ['admin', 'organizer'], true)) {
            return $next($request);
        }

        if (Auth::check() && in_array((string) Auth::user()->role, ['admin', 'organizer'], true)) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('auth_role', Auth::user()->role);

            return $next($request);
        }

        $request->session()->put('admin_intended', $request->fullUrl());

        return redirect()->route('organizer.login');
    }
}
