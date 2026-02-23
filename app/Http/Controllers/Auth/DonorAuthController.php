<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DonorAuthController extends Controller
{
    public function showAuth(Request $request)
    {
        $mode = $request->query('mode', 'login');
        $role = $request->query('role', 'donor');

        if (! in_array($mode, ['login', 'register'], true)) {
            $mode = 'login';
        }

        if (! in_array($role, ['donor', 'organizer'], true)) {
            $role = 'donor';
        }

        return view('auth.portal', compact('mode', 'role'));
    }

    public function showLogin(Request $request)
    {
        return redirect()->route('auth.page', ['mode' => 'login', 'role' => $request->query('role', 'donor')]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'role' => 'nullable|in:donor,organizer',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $selectedRole = $credentials['role'] ?? 'donor';

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], true)) {
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

        if ($user && $selectedRole === 'organizer' && ! in_array($user->role, ['organizer', 'admin'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account is not registered as organizer.'])
                ->withInput();
        }

        if ($user && in_array($user->role, ['admin', 'organizer'], true)) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('auth_role', $user->role);
        } else {
            $request->session()->forget(['admin_logged_in', 'auth_role', 'admin_intended']);
        }

        return redirect()->intended(route('campaigns.index'));
    }

    public function showRegister(Request $request)
    {
        return redirect()->route('auth.page', ['mode' => 'register', 'role' => $request->query('role', 'donor')]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'role' => 'nullable|in:donor,organizer',
            'name' => 'required|string|max:255',
            'alias_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $selectedRole = $data['role'] ?? 'donor';

        $aliasName = trim((string) ($data['alias_name'] ?? ''));
        $aliasName = $aliasName === '' ? null : $aliasName;

        $user = User::create([
            'name' => $data['name'],
            'alias_name' => $aliasName,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $selectedRole,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        if (in_array($user->role, ['admin', 'organizer'], true)) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('auth_role', $user->role);
        } else {
            $request->session()->forget(['admin_logged_in', 'auth_role', 'admin_intended']);
        }

        return redirect()
            ->route('campaigns.index')
            ->with('status', 'Account created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('campaigns.index');
    }
}
