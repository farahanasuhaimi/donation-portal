<x-layouts.app :title="'Create Campaign'">
    <div class="max-w-3xl">
        <h1 class="text-2xl font-semibold">Create Campaign</h1>
        <p class="mt-2 text-sm text-slate-300">Add a new fundraising campaign.</p>

        <form class="mt-6 space-y-4" method="post" action="{{ route('admin.campaigns.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="text-xs text-slate-400">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
            </div>
            <div>
                <label class="text-xs text-slate-400">Description</label>
                <textarea name="description" rows="5" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required>{{ old('description') }}</textarea>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-xs text-slate-400">Target Amount (RM)</label>
                    <input type="number" step="0.01" min="1" name="target_amount" value="{{ old('target_amount') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-400">QR Image</label>
                <input type="file" name="qr_image" accept="image/*" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
            </div>
            <button type="submit" class="rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300">
                Save Campaign
            </button>
        </form>
    </div>
</x-layouts.app>
