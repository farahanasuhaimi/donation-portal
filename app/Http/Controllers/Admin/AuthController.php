<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $adminEmail = config('admin.email');
        $adminPassword = config('admin.password');

        if ($adminEmail && $adminPassword
            && $data['email'] === $adminEmail
            && $data['password'] === $adminPassword) {
            $request->session()->put('admin_logged_in', true);

            $intended = $request->session()->pull('admin_intended', route('admin.dashboard'));

            return redirect()->to($intended);
        }

        return back()
            ->withErrors(['email' => 'Invalid admin credentials.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_intended']);

        return redirect()->route('admin.login');
    }
}
