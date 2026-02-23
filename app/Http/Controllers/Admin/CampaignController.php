<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::query()
            ->withSum('donations', 'amount')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'qr_image' => 'required|image|max:2048',
        ]);

        $path = $request->file('qr_image')->store('qr_images', 'public');

        $campaign = Campaign::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'target_amount' => $data['target_amount'],
            'deadline' => $data['deadline'],
            'qr_image' => $path,
        ]);

        return redirect()
            ->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Campaign created.');
    }

    public function edit(Campaign $campaign)
    {
        $campaign->loadSum('donations', 'amount');
        $donations = $campaign->donations()->orderByDesc('created_at')->get();

        return view('admin.campaigns.edit', compact('campaign', 'donations'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'required|date|after_or_equal:today',
            'qr_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('qr_image')) {
            if ($campaign->qr_image) {
                Storage::disk('public')->delete($campaign->qr_image);
            }

            $campaign->qr_image = $request->file('qr_image')->store('qr_images', 'public');
        }

        $campaign->fill([
            'title' => $data['title'],
            'description' => $data['description'],
            'target_amount' => $data['target_amount'],
            'deadline' => $data['deadline'],
        ]);

        $campaign->save();

        return redirect()
            ->route('admin.campaigns.edit', $campaign)
            ->with('status', 'Campaign updated.');
    }
}
