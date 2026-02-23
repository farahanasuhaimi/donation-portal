<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $registeredOrganizers = User::query()
            ->where('role', 'organizer')
            ->orderByDesc('created_at')
            ->get();

        $registeredDonors = User::query()
            ->where('role', 'donor')
            ->orderByDesc('created_at')
            ->get();

        $unregisteredDonors = Donation::query()
            ->whereNull('donor_user_id')
            ->select([
                'donor_name',
                'donor_mobile',
                DB::raw('COUNT(*) as donation_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MAX(created_at) as last_donation_at'),
            ])
            ->groupBy('donor_name', 'donor_mobile')
            ->orderByDesc('last_donation_at')
            ->get();

        return view('admin.users.index', compact(
            'registeredOrganizers',
            'registeredDonors',
            'unregisteredDonors'
        ));
    }
}
