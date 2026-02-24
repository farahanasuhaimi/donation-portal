<x-layouts.app :title="'Log Masuk Admin'">
    <div class="mx-auto max-w-sm rounded-xl border border-white/10 bg-slate-900/60 p-6">
        <h1 class="text-xl font-semibold">Log Masuk Admin</h1>
        <p class="mt-1 text-sm text-slate-400">Guna emel dan kata laluan admin.</p>

        <form class="mt-5 space-y-3" method="post" action="{{ route('admin.login.submit') }}">
            @csrf
            <div>
                <label class="text-xs text-slate-400">Emel</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm"
                    required
                />
            </div>
            <div>
                <label class="text-xs text-slate-400">Kata Laluan</label>
                <input
                    type="password"
                    name="password"
                    class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm"
                    required
                />
            </div>
            <button
                type="submit"
                class="w-full rounded-md bg-emerald-400 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-emerald-300"
            >
                Log Masuk
            </button>
        </form>
    </div>
</x-layouts.app>
