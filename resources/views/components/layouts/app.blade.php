<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $title ?? 'Portal Infaq Ramadan' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="bg-gradient-to-b from-emerald-950 via-slate-950 to-slate-950">
            <header class="border-b border-white/10">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                    <a href="{{ route('campaigns.index') }}" class="text-lg font-semibold tracking-wide">
                        Portal Infaq Ramadan
                    </a>
                    <nav class="flex items-center gap-4 text-sm text-slate-300">
                        <a href="{{ route('campaigns.index') }}" class="hover:text-white">Campaigns</a>
                        @if (session('admin_logged_in'))
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-white">
                                {{ session('auth_role') === 'organizer' ? 'Organizer Panel' : 'Admin Panel' }}
                            </a>
                            <form
                                method="post"
                                action="{{ session('auth_role') === 'organizer' ? route('donor.logout') : route('admin.logout') }}"
                            >
                                @csrf
                                <button type="submit" class="hover:text-white">Logout</button>
                            </form>
                        @elseif (auth()->check())
                            <span class="text-slate-400">Hi, {{ auth()->user()->name }}</span>
                            @if (auth()->user()->role === 'organizer')
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-white">Organizer Panel</a>
                            @endif
                            <form method="post" action="{{ route('donor.logout') }}">
                                @csrf
                                <button type="submit" class="hover:text-white">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('auth.page') }}" class="hover:text-white">Account</a>
                            <a href="{{ route('admin.login') }}" class="hover:text-white">Admin Login</a>
                        @endif
                    </nav>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-6 py-10">
                @if (session('status'))
                    <div
                        id="flash-toast"
                        data-timeout="4000"
                        class="fixed right-6 top-6 z-50 w-[min(90vw,380px)] rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100 shadow-lg backdrop-blur transition duration-200"
                        role="status"
                    >
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 h-2.5 w-2.5 flex-none rounded-full bg-emerald-300"></div>
                            <p class="flex-1">{{ session('status') }}</p>
                            <button
                                type="button"
                                data-toast-close
                                class="rounded-md px-2 py-1 text-xs text-emerald-100/80 hover:text-white"
                                aria-label="Close notification"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
