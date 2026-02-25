<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonorProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'donor') {
            abort(403);
        }

        $from = now()->subDays(29)->startOfDay();
        $to = now()->endOfDay();

        $totalLast30Days = (float) Donation::query()
            ->where('donor_user_id', $user->id)
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');
        $lifetimeTotal = (float) Donation::query()
            ->where('donor_user_id', $user->id)
            ->sum('amount');

        return view('donor.profile', [
            'user' => $user,
            'totalLast30Days' => $totalLast30Days,
            'lifetimeTotal' => $lifetimeTotal,
        ]);
    }
}
