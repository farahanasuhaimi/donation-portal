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
                <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <a href="{{ route('campaigns.index') }}" class="text-center text-lg font-semibold tracking-wide sm:text-left">
                        Portal Infaq Ramadan
                    </a>
                    <nav class="flex w-full flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm text-slate-300 sm:w-auto sm:justify-end">
                        <a href="{{ route('campaigns.index') }}" class="hover:text-white">Kempen Derma</a>
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

            <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                        {{ session('status') }}
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
