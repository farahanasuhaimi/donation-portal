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
            'donor_alias_name' => 'nullable|string|max:255',
            'donor_mobile' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:1',
        ]);

        $realName = trim($data['donor_name']);
        $aliasName = trim((string) ($data['donor_alias_name'] ?? ''));
        $aliasName = $aliasName === '' ? null : $aliasName;
        $publicName = $aliasName ?: $realName;
        $mobile = $data['donor_mobile'] ?? null;
        $mobile = $mobile === '' ? null : $mobile;
        $donorUserId = $request->user()?->id;

        Donation::create([
            'campaign_id' => $campaign->id,
            'donor_user_id' => $donorUserId,
            'donor_name' => $publicName,
            'donor_real_name' => $realName,
            'donor_alias_name' => $aliasName,
            'donor_mobile' => $mobile,
            'amount' => $data['amount'],
            'created_at' => now(),
        ]);

        $request->session()->put('donor_name', $realName);
        $request->session()->put('donor_alias_name', $aliasName);
        $request->session()->put('donor_mobile', $mobile);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('status', 'Thank you for your donation pledge!');
    }
}
