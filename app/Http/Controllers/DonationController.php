<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        if (! $campaign->isActive()) {
            return back()->withErrors(['amount' => 'This campaign is closed.'])->withInput();
        }

        $data = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_mobile' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:1',
        ]);

        $mobile = $data['donor_mobile'] ?? null;
        $mobile = $mobile === '' ? null : $mobile;

        Donation::create([
            'campaign_id' => $campaign->id,
            'donor_name' => $data['donor_name'],
            'donor_mobile' => $mobile,
            'amount' => $data['amount'],
            'created_at' => now(),
        ]);

        $request->session()->put('donor_name', $data['donor_name']);
        $request->session()->put('donor_mobile', $mobile);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('status', 'Thank you for your donation pledge!');
    }
}
