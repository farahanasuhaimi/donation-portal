<x-layouts.app>
    @php
        $collected = (float) ($campaign->donations_sum_amount ?? 0);
        $target = (float) $campaign->target_amount;
        $progress = $target > 0 ? min(100, ($collected / $target) * 100) : 0;
    @endphp

    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-semibold">{{ $campaign->title }}</h1>
                    <p class="mt-3 text-slate-300">{{ $campaign->description }}</p>
                </div>
                @if ($campaign->isAchieved())
                    <span class="rounded-full bg-sky-500/20 px-3 py-1 text-xs text-sky-200">Achieved</span>
                @elseif ($campaign->isActive())
                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs text-emerald-200">Active</span>
                @else
                    <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs text-rose-200">Closed</span>
                @endif
            </div>

            <div class="mt-8">
                <div class="h-2 w-full rounded-full bg-white/10">
                    <div class="h-2 rounded-full bg-emerald-400" style="width: {{ $progress }}%"></div>
                </div>
                <div class="mt-3 flex flex-wrap items-center justify-between gap-4 text-sm text-slate-300">
                    <span>Raised RM {{ number_format($collected, 2) }} of RM {{ number_format($target, 2) }}</span>
                    <span>Deadline: {{ $campaign->deadline->format('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-8 grid gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Scan QR</p>
                    <img
                        class="mt-4 w-full rounded-lg border border-white/10 bg-white p-2 {{ ! $campaign->isActive() ? 'blur-sm opacity-50' : '' }}"
                        src="{{ \Illuminate\Support\Facades\Storage::url($campaign->qr_image) }}"
                        alt="QR for {{ $campaign->title }}"
                    />
                </div>
                <div class="rounded-xl border border-white/10 bg-slate-950/40 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Donation Pledge</p>
                    <form class="mt-4 space-y-4" method="post" action="{{ route('campaigns.donate', $campaign) }}">
                        @csrf
                        <div>
                            <label class="text-xs text-slate-400">Full Name</label>
                            <input
                                type="text"
                                name="donor_name"
                                value="{{ old('donor_name', $donorName) }}"
                                class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                                required
                                {{ auth()->check() ? 'readonly' : '' }}
                            />
                            @if (auth()->check())
                                <p class="mt-1 text-xs text-slate-500">Using your account name for this donation.</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-xs text-slate-400">Nama Samaran (optional)</label>
                            <input
                                type="text"
                                name="donor_alias_name"
                                value="{{ old('donor_alias_name', $donorAliasName) }}"
                                class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                            />
                            <p class="mt-1 text-xs text-slate-500">If filled, this name will be shown publicly instead of your real name.</p>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400">Mobile (optional)</label>
                            <input
                                type="text"
                                name="donor_mobile"
                                value="{{ old('donor_mobile', $donorMobile) }}"
                                class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                            />
                        </div>
                        <div>
                            <label class="text-xs text-slate-400">Amount (RM)</label>
                            <input
                                type="number"
                                step="0.01"
                                min="1"
                                name="amount"
                                value="{{ old('amount') }}"
                                class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                                required
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300 disabled:opacity-60"
                            {{ $campaign->isActive() ? '' : 'disabled' }}
                        >
                            Submit Sedekah
                        </button>
                    </form>

                    <button
                        type="button"
                        data-copy-value="{{ route('campaigns.share', $campaign->share_token) }}"
                        data-copy-label="Link copied"
                        onclick="copyShareLink(this)"
                        class="mt-4 w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-200 hover:bg-slate-800"
                    >
                        Share
                    </button>

                    @if ($campaign->isAchieved())
                        <p class="mt-3 text-xs text-sky-200">Target achieved. This campaign is fulfilled.</p>
                    @elseif (! $campaign->isActive())
                        <p class="mt-3 text-xs text-rose-200">This campaign is closed.</p>
                    @endif

                    @if ($donorTotal !== null)
                        <div class="mt-5 rounded-lg border border-emerald-400/30 bg-emerald-500/10 px-3 py-2 text-xs text-emerald-100">
                            Your pledged total: RM {{ number_format($donorTotal, 2) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-lg font-semibold">Recent Donations</h2>
            <p class="mt-2 text-sm text-slate-300">Latest 10 pledges for this campaign.</p>
            <div class="mt-6 space-y-4">
                @forelse ($donations as $donation)
                    <div class="flex items-center justify-between rounded-lg border border-white/10 bg-slate-950/40 px-4 py-3 text-sm">
                        <div>
                            <p class="font-medium">{{ $donation->donor_name }}</p>
                            <p class="text-xs text-slate-400">{{ $donation->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="text-emerald-200">RM {{ number_format($donation->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">No donations yet. Be the first!</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
