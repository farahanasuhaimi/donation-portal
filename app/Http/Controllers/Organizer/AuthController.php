<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('organizer.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $organizerEmail = config('organizer.email');
        $organizerPassword = config('organizer.password');

        if ($organizerEmail && $organizerPassword
            && $data['email'] === $organizerEmail
            && $data['password'] === $organizerPassword) {
            // Reuse existing admin-protected dashboard permissions.
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('auth_role', 'organizer');

            $intended = $request->session()->pull('admin_intended', route('admin.dashboard'));

            return redirect()->to($intended);
        }

        return back()
            ->withErrors(['email' => 'Invalid organizer credentials.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_intended', 'auth_role']);

        return redirect()->route('organizer.login');
    }
}
