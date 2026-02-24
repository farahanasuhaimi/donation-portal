<x-layouts.app :title="'Admin Dashboard'">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
            <p class="mt-2 text-sm text-slate-300">Manage campaigns and track donations.</p>
        </div>
        <div class="flex items-center gap-3">
            @if (($role ?? null) === 'admin')
                <a
                    href="{{ route('admin.users.index') }}"
                    class="rounded-full border border-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 hover:bg-white/10"
                >
                    Users
                </a>
            @endif
            <a
                href="{{ route('admin.campaigns.create') }}"
                class="rounded-full bg-emerald-400 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300"
            >
                Add Campaign
            </a>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-white/10">
        <div class="divide-y divide-white/10 md:hidden">
            @forelse ($campaigns as $campaign)
                <div class="bg-slate-950/30 p-4">
                    <div class="flex flex-wrap items-start gap-4">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $campaign->title }}</p>
                            <p class="mt-1 text-xs text-slate-400">Deadline: {{ $campaign->deadline->format('d M Y') }}</p>
                        </div>
                        <div class="text-xs text-slate-400">
                            <p class="text-xs uppercase tracking-wide text-slate-400">Status</p>
                            <div class="mt-1">
                                @if ($campaign->isArchived())
                                    <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs text-amber-200">Archived</span>
                                @elseif ($campaign->isAchieved())
                                    <span class="rounded-full bg-sky-500/20 px-3 py-1 text-xs text-sky-200">Achieved</span>
                                @elseif ($campaign->isActive())
                                    <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs text-emerald-200">Active</span>
                                @else
                                    <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs text-rose-200">Closed</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-slate-300">
                        <div class="rounded-xl bg-white/5 p-3">
                            <p class="text-[11px] uppercase tracking-wide text-slate-400">Raised</p>
                            <p class="mt-1 text-sm font-semibold text-emerald-200">RM {{ number_format($campaign->donations_sum_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="rounded-xl bg-white/5 p-3">
                            <p class="text-[11px] uppercase tracking-wide text-slate-400">Target</p>
                            <p class="mt-1 text-sm font-semibold">RM {{ number_format($campaign->target_amount, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                        @if (! $campaign->isArchived() || ($role ?? null) === 'admin')
                            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-emerald-200 hover:text-emerald-100">
                                Edit / Donors
                            </a>
                        @endif
                        @if (! $campaign->isArchived())
                            <form method="post" action="{{ route('admin.campaigns.destroy', $campaign) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="text-xs text-rose-200 hover:text-rose-100">
                                    Archive
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-4 text-sm text-slate-400">No campaigns yet.</div>
            @endforelse
        </div>

        <table class="hidden w-full text-left text-sm md:table">
            <thead class="bg-white/5 text-xs uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Raised</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3">Deadline</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse ($campaigns as $campaign)
                    <tr class="bg-slate-950/30">
                        <td class="px-4 py-3 font-medium">{{ $campaign->title }}</td>
                        <td class="px-4 py-3 text-emerald-200">RM {{ number_format($campaign->donations_sum_amount ?? 0, 2) }}</td>
                        <td class="px-4 py-3">RM {{ number_format($campaign->target_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $campaign->deadline->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @if ($campaign->isArchived())
                                <span class="rounded-full bg-amber-500/20 px-3 py-1 text-xs text-amber-200">Archived</span>
                            @elseif ($campaign->isAchieved())
                                <span class="rounded-full bg-sky-500/20 px-3 py-1 text-xs text-sky-200">Achieved</span>
                            @elseif ($campaign->isActive())
                                <span class="rounded-full bg-emerald-500/20 px-3 py-1 text-xs text-emerald-200">Active</span>
                            @else
                                <span class="rounded-full bg-rose-500/20 px-3 py-1 text-xs text-rose-200">Closed</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if (! $campaign->isArchived() || ($role ?? null) === 'admin')
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-emerald-200 hover:text-emerald-100">
                                    Edit / Donors
                                </a>
                            @endif
                            @if (! $campaign->isArchived())
                                <form method="post" action="{{ route('admin.campaigns.destroy', $campaign) }}" class="mt-2">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-xs text-rose-200 hover:text-rose-100">
                                        Archive
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-4 text-slate-400" colspan="6">No campaigns yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
