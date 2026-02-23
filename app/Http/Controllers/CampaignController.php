<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::query()
            ->withSum('donations', 'amount')
            ->orderByDesc('created_at')
            ->get();

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Request $request, Campaign $campaign)
    {
        $campaign->loadSum('donations', 'amount');

        $donations = $campaign->donations()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $donorName = $request->session()->get('donor_name');
        $donorMobile = $request->session()->get('donor_mobile');
        $donorTotal = null;

        if ($donorName) {
            $donorTotal = Donation::query()
                ->where('campaign_id', $campaign->id)
                ->where('donor_name', $donorName)
                ->when(
                    $donorMobile !== null && $donorMobile !== '',
                    fn ($query) => $query->where('donor_mobile', $donorMobile),
                    fn ($query) => $query->whereNull('donor_mobile')
                )
                ->sum('amount');
        }

        return view('campaigns.show', compact('campaign', 'donations', 'donorTotal', 'donorName', 'donorMobile'));
    }
}
