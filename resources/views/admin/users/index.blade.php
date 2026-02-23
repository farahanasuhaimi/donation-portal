<x-layouts.app :title="'Users Overview'">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold">Users Overview</h1>
            <p class="mt-2 text-sm text-slate-300">Registered organizers, registered donors, and unregistered donors from donation records.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-300 hover:text-white">Back to dashboard</a>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-300">Registered Organizers</h2>
            </div>
            <div class="divide-y divide-white/10">
                @forelse ($registeredOrganizers as $user)
                    <div class="bg-slate-950/30 px-4 py-3 text-sm">
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                    </div>
                @empty
                    <div class="px-4 py-4 text-sm text-slate-400">No registered organizers yet.</div>
                @endforelse
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/10">
            <div class="border-b border-white/10 bg-white/5 px-4 py-3">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-300">Registered Donors</h2>
            </div>
            <div class="divide-y divide-white/10">
                @forelse ($registeredDonors as $user)
                    <div class="bg-slate-950/30 px-4 py-3 text-sm">
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                    </div>
                @empty
                    <div class="px-4 py-4 text-sm text-slate-400">No registered donors yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-2xl border border-white/10">
        <div class="border-b border-white/10 bg-white/5 px-4 py-3">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-300">Unregistered Donors</h2>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-white/5 text-xs uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Mobile</th>
                    <th class="px-4 py-3">Donations</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Last Donation</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse ($unregisteredDonors as $donor)
                    <tr class="bg-slate-950/30">
                        <td class="px-4 py-3 font-medium">{{ $donor->donor_name }}</td>
                        <td class="px-4 py-3">{{ $donor->donor_mobile ?: 'No mobile' }}</td>
                        <td class="px-4 py-3">{{ $donor->donation_count }}</td>
                        <td class="px-4 py-3 text-emerald-200">RM {{ number_format((float) $donor->total_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($donor->last_donation_at)->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-slate-400">No unregistered donors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
