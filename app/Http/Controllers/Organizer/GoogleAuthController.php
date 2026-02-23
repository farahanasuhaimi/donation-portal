<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::query()
            ->where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->name ?: 'Organizer',
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'role' => 'organizer',
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
                $user->role = 'organizer';
            }

            $user->save();
        }

        Auth::login($user, true);

        // Preserve existing session-based middleware logic.
        $requestRole = $user->role === 'admin' ? 'admin' : 'organizer';
        session([
            'admin_logged_in' => true,
            'auth_role' => $requestRole,
        ]);

        return redirect()->route('admin.dashboard');
    }
}
