<x-layouts.app :title="'My Sedekah'">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-semibold">My Sedekah</h1>
            <p class="mt-2 text-sm text-slate-300">Hello {{ $user->name }}, here is your contribution summary.</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="text-sm text-slate-300 hover:text-white">Browse campaigns</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-lg font-semibold">Last 30 Days</h2>
            <p class="mt-2 text-2xl font-semibold text-emerald-200">RM {{ number_format($totalLast30Days, 2) }}</p>
            <p class="mt-2 text-xs text-slate-500">Total sedekah you pledged in the last 30 days.</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-lg font-semibold">All Time</h2>
            <p class="mt-2 text-2xl font-semibold text-emerald-200">RM {{ number_format($lifetimeTotal, 2) }}</p>
            <p class="mt-2 text-xs text-slate-500">Includes all confirmed and pending sedekah.</p>
        </div>
    </div>
</x-layouts.app>
