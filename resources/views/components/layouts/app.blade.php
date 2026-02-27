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
                            @if (auth()->user()->role === 'donor')
                                <a href="{{ route('donor.profile') }}" class="hover:text-white">My Sedekah</a>
                            @endif
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
                        class="fixed left-1/2 top-8 z-[100] w-[min(90vw,420px)] -translate-x-1/2 overflow-hidden rounded-2xl border border-emerald-400/30 bg-slate-900/40 p-1 shadow-2xl backdrop-blur-xl transition-all duration-500 translate-y-[-20px] opacity-0"
                        role="status"
                    >
                        <div class="rounded-[14px] bg-gradient-to-br from-emerald-500/20 to-transparent px-5 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-emerald-400/20 text-emerald-400 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400/70">Success</p>
                                    <p class="mt-0.5 text-sm font-medium text-emerald-50">{{ session('status') }}</p>
                                </div>
                                <button
                                    type="button"
                                    onclick="dismissToast()"
                                    class="rounded-lg p-1 text-emerald-100/50 hover:bg-emerald-400/10 hover:text-white"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="toast-progress" class="h-1 w-0 bg-emerald-400/40 transition-none"></div>
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

        <script>
            // Toast Management
            function dismissToast() {
                const toast = document.getElementById('flash-toast');
                if (toast) {
                    toast.classList.add('translate-y-[-20px]', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const toast = document.getElementById('flash-toast');
                const progress = document.getElementById('toast-progress');
                
                if (toast) {
                    // Entry Animation
                    setTimeout(() => {
                        toast.classList.remove('translate-y-[-20px]', 'opacity-0');
                    }, 100);

                    // Progress Bar & Auto-dismiss
                    if (progress) {
                        let width = 0;
                        const interval = 10; // ms
                        const duration = 4000; // ms
                        const step = (interval / duration) * 100;

                        const timer = setInterval(() => {
                            width += step;
                            progress.style.width = width + '%';
                            if (width >= 100) {
                                clearInterval(timer);
                                dismissToast();
                            }
                        }, interval);
                    }
                }
            });

            window.copyShareLink = async function (button) {
                if (!button) return;

                const directValue = button.getAttribute('data-copy-value');
                const selector = button.getAttribute('data-copy-target');
                const input = selector ? document.querySelector(selector) : null;
                const value = directValue || (input ? input.value : '');
                if (!value) return;

                const showCopied = () => {
                    const label = button.getAttribute('data-copy-label') || 'Copied';
                    const original = button.textContent;
                    button.textContent = label;
                    button.disabled = true;
                    window.setTimeout(() => {
                        button.textContent = original;
                        button.disabled = false;
                    }, 1500);
                };

                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(value);
                        showCopied();
                        return;
                    }
                } catch (error) {
                    // Fall through to legacy copy.
                }

                const fallback = document.createElement('textarea');
                fallback.value = value;
                fallback.setAttribute('readonly', '');
                fallback.style.position = 'absolute';
                fallback.style.left = '-9999px';
                document.body.appendChild(fallback);
                fallback.select();
                const copied = document.execCommand('copy');
                fallback.remove();
                if (copied) {
                    showCopied();
                }
            };
        </script>
    </body>
</html>
