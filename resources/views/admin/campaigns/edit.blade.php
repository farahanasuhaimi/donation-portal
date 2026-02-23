<x-layouts.app :title="'Edit Campaign'">
    @php
        $collected = (float) ($campaign->donations_sum_amount ?? 0);
    @endphp

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
        <div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Edit Campaign</h1>
                    <p class="mt-2 text-sm text-slate-300">Update campaign details and QR image.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-300 hover:text-white">Back to dashboard</a>
            </div>

            <form class="mt-6 space-y-4" method="post" action="{{ route('admin.campaigns.update', $campaign) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div>
                    <label class="text-xs text-slate-400">Title</label>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Description</label>
                    <textarea name="description" rows="5" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required>{{ old('description', $campaign->description) }}</textarea>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs text-slate-400">Target Amount (RM)</label>
                        <input type="number" step="0.01" min="1" name="target_amount" value="{{ old('target_amount', $campaign->target_amount) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                    </div>
                    <div>
                        <label class="text-xs text-slate-400">Deadline</label>
                        <input type="date" name="deadline" value="{{ old('deadline', $campaign->deadline->format('Y-m-d')) }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                    </div>
                </div>
                <div>
                    <label class="text-xs text-slate-400">Replace QR Image</label>
                    <input type="file" name="qr_image" accept="image/*" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" />
                </div>
                @if ($campaign->qr_image)
                    <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Current QR</p>
                        <img
                            class="mt-4 w-full rounded-lg border border-white/10 bg-white p-2"
                            src="{{ \Illuminate\Support\Facades\Storage::url($campaign->qr_image) }}"
                            alt="QR for {{ $campaign->title }}"
                        />
                    </div>
                @endif
                <button type="submit" class="rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300">
                    Update Campaign
                </button>
            </form>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-lg font-semibold">Donor History</h2>
            <p class="mt-2 text-sm text-slate-300">
                Total collected: RM {{ number_format($collected, 2) }}
            </p>
            <div class="mt-6 space-y-3">
                @forelse ($donations as $donation)
                    <div class="flex items-center justify-between rounded-lg border border-white/10 bg-slate-950/40 px-4 py-3 text-sm">
                        <div>
                            <p class="font-medium">{{ $donation->donor_name }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $donation->donor_mobile ?: 'No mobile' }} · {{ $donation->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="text-emerald-200">RM {{ number_format($donation->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No donations yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
