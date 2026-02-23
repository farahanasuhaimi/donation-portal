<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return redirect()->route('auth.page', ['mode' => 'login', 'role' => 'organizer']);
    }

    public function login(Request $request)
    {
        return redirect()->route('auth.page', ['mode' => 'login', 'role' => 'organizer']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('organizer.login');
    }
}
