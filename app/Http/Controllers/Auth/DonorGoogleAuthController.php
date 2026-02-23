<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class DonorGoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $role = $request->query('role', 'donor');
        if (! in_array($role, ['donor', 'organizer'], true)) {
            $role = 'donor';
        }

        $request->session()->put('oauth_target_role', $role);

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback()
    {
        $targetRole = session()->pull('oauth_target_role', 'donor');
        if (! in_array($targetRole, ['donor', 'organizer'], true)) {
            $targetRole = 'donor';
        }

        $googleUser = Socialite::driver('google')->user();

        $user = User::query()
            ->where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->name ?: 'Donor',
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'role' => $targetRole,
            ]);
        } else {
            $user->fill([
                'google_id' => $user->google_id ?: $googleUser->id,
                'avatar' => $googleUser->avatar ?: $user->avatar,
            ]);

            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }

            if (! $user->role) {
                $user->role = $targetRole;
            }

            if ($targetRole === 'organizer' && $user->role === 'donor') {
                $user->role = 'organizer';
            }

            $user->save();
        }

        Auth::login($user, true);

        if (in_array($user->role, ['admin', 'organizer'], true)) {
            session([
                'admin_logged_in' => true,
                'auth_role' => $user->role,
            ]);
        } else {
            session()->forget(['admin_logged_in', 'auth_role', 'admin_intended']);
        }

        if (in_array($user->role, ['admin', 'organizer'], true)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('campaigns.index');
    }
}
