<x-layouts.app :title="'Donor Login'">
    <div class="mx-auto max-w-md rounded-2xl border border-white/10 bg-white/5 p-8">
        <h1 class="text-2xl font-semibold">Donor Login</h1>
        <p class="mt-2 text-sm text-slate-300">Sign in to track your donations and donate faster.</p>

        <a
            href="{{ route('donor.google.redirect') }}"
            class="mt-6 block w-full rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-center text-sm font-semibold hover:bg-white/20"
        >
            Continue with Google
        </a>

        <p class="mt-5 text-xs text-slate-400">Or login with email:</p>

        <form class="mt-3 space-y-4" method="post" action="{{ route('donor.login.submit') }}">
            @csrf
            <div>
                <label class="text-xs text-slate-400">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                    required
                />
            </div>
            <div>
                <label class="text-xs text-slate-400">Password</label>
                <input
                    type="password"
                    name="password"
                    class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm"
                    required
                />
            </div>
            <button
                type="submit"
                class="w-full rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300"
            >
                Sign In
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-slate-400">
            No account?
            <a class="text-emerald-200 hover:text-emerald-100" href="{{ route('donor.register') }}">Create one</a>
        </p>
    </div>
</x-layouts.app>
