<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class DonorGoogleAuthController extends Controller
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
                'name' => $googleUser->name ?: 'Donor',
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'role' => 'donor',
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
                $user->role = 'donor';
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

        return redirect()->route('campaigns.index');
    }
}
