<x-layouts.app>
    <div class="mb-10">
        <h1 class="text-3xl font-semibold">Support Ramadan Campaigns</h1>
        <p class="mt-2 text-slate-300">Browse community initiatives and pledge your donations.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @forelse ($campaigns as $campaign)
            @php
                $collected = (float) ($campaign->donations_sum_amount ?? 0);
                $target = (float) $campaign->target_amount;
                $progress = $target > 0 ? min(100, ($collected / $target) * 100) : 0;
            @endphp
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-black/20">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $campaign->title }}</h2>
                        <p class="mt-2 text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($campaign->description, 140) }}</p>
                    </div>
                    @if ($campaign->isAchieved())
                        <span class="rounded-full bg-sky-500/20 px-3 py-1 text-xs text-sky-200">Achieved</span>
                    @elseif ($campaign->isActive())
                        <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs text-emerald-200">Active</span>
                    @else
                        <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs text-rose-200">Closed</span>
                    @endif
                </div>

                <div class="mt-6">
                    <div class="h-2 w-full rounded-full bg-white/10">
                        <div class="h-2 rounded-full bg-emerald-400" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-slate-300">
                        <span>Collected: RM {{ number_format($collected, 2) }}</span>
                        <span>Target: RM {{ number_format($target, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Deadline: {{ $campaign->deadline->format('d M Y') }}</span>
                    <a
                        href="{{ route('campaigns.show', $campaign) }}"
                        class="rounded-full bg-emerald-400 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300"
                    >
                        Donate
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-slate-300">
                No campaigns yet. Check back soon.
            </div>
        @endforelse
    </div>
</x-layouts.app>
