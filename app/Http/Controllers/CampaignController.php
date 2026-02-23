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

        $authUser = $request->user();
        $donorName = $authUser?->name ?? $request->session()->get('donor_name');
        $donorAliasName = $request->session()->get('donor_alias_name');
        $donorMobile = $request->session()->get('donor_mobile');
        $donorTotal = null;

        if ($authUser) {
            $donorTotal = Donation::query()
                ->where('campaign_id', $campaign->id)
                ->where('donor_user_id', $authUser->id)
                ->sum('amount');
        } elseif ($donorName) {
            $donorTotal = Donation::query()
            ->where('campaign_id', $campaign->id)
            ->where(function ($query) use ($donorName) {
                $query->where('donor_real_name', $donorName)
                    ->orWhere(function ($inner) use ($donorName) {
                        $inner->whereNull('donor_real_name')
                            ->where('donor_name', $donorName);
                    });
            })
            ->when(
                $donorMobile !== null && $donorMobile !== '',
                fn ($query) => $query->where('donor_mobile', $donorMobile),
                fn ($query) => $query->whereNull('donor_mobile')
            )
            ->sum('amount');
        }

        return view('campaigns.show', compact('campaign', 'donations', 'donorTotal', 'donorName', 'donorAliasName', 'donorMobile'));
    }
}
