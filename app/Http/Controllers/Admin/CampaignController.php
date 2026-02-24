<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $role = (string) ($request->session()->get('auth_role') ?: $request->user()?->role);
        $userId = $request->user()?->id;

        if ($role === 'organizer' && ! $userId) {
            abort(403, 'Organizer account is required.');
        }

        $campaigns = Campaign::query()
            ->withSum('donations', 'amount')
            ->when(
                $role === 'organizer',
                fn ($query) => $query
                    ->where('organizer_user_id', $userId)
                    ->whereNull('archived_at')
            )
            ->orderByDesc('created_at')
            ->get();

        return view('admin.campaigns.index', compact('campaigns', 'role'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $role = (string) ($request->session()->get('auth_role') ?: $request->user()?->role);
        $userId = $request->user()?->id;

        if ($role === 'organizer' && ! $userId) {
            abort(403, 'Organizer account is required.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'required|date|after:today',
            'qr_image' => 'required|image|max:2048',
        ]);

        $path = $request->file('qr_image')->store('qr_images', 'public');

        $campaign = Campaign::create([
            'organizer_user_id' => $role === 'organizer' ? $userId : null,
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

    public function edit(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($request, $campaign);

        $campaign->loadSum('donations', 'amount');
        $donations = $campaign->donations()->orderByDesc('created_at')->get();

        return view('admin.campaigns.edit', compact('campaign', 'donations'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($request, $campaign);

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

    public function destroy(Request $request, Campaign $campaign)
    {
        $this->authorizeCampaign($request, $campaign);

        if ($campaign->isArchived()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Campaign is already archived.');
        }

        $campaign->archived_at = now();
        $campaign->archived_by_user_id = $request->user()?->id;
        $campaign->save();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Campaign moved to archive.');
    }

    public function confirmDonation(Request $request, Campaign $campaign, Donation $donation)
    {
        $this->authorizeCampaign($request, $campaign);

        if ((int) $donation->campaign_id !== (int) $campaign->id) {
            abort(404);
        }

        $data = $request->validate([
            'confirmed' => 'required|boolean',
        ]);

        $isConfirmed = (bool) $data['confirmed'];
        $donation->is_confirmed = $isConfirmed;
        $donation->confirmed_at = $isConfirmed ? now() : null;
        $donation->save();

        return redirect()
            ->route('admin.campaigns.edit', $campaign)
            ->with('status', $isConfirmed ? 'Donation confirmed.' : 'Donation marked as pending.');
    }

    private function authorizeCampaign(Request $request, Campaign $campaign): void
    {
        $role = (string) ($request->session()->get('auth_role') ?: $request->user()?->role);
        $userId = $request->user()?->id;

        if ($role === 'organizer' && $campaign->isArchived()) {
            abort(403, 'Archived campaigns are only visible to admin.');
        }

        if ($role === 'organizer' && (! $userId || (int) $campaign->organizer_user_id !== (int) $userId)) {
            abort(403, 'You can only manage campaigns you created.');
        }
    }
}
