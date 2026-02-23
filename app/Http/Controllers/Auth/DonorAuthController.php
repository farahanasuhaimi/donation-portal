<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DonorAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, true)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->withInput();
        }

        $request->session()->regenerate();

        $user = $request->user();
        if ($user && ! $user->role) {
            $user->role = 'donor';
            $user->save();
        }

        if ($user && in_array($user->role, ['admin', 'organizer'], true)) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('auth_role', $user->role);
        } else {
            $request->session()->forget(['admin_logged_in', 'auth_role', 'admin_intended']);
        }

        return redirect()->intended(route('campaigns.index'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'donor',
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->forget(['admin_logged_in', 'auth_role', 'admin_intended']);

        return redirect()
            ->route('campaigns.index')
            ->with('status', 'Account created. You can now donate as a donor.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('campaigns.index');
    }
}
