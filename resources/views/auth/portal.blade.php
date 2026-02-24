<x-layouts.app :title="'Akaun'">
    <div class="mx-auto max-w-lg rounded-xl border border-white/10 bg-slate-900/60 p-6">
        <h1 class="text-xl font-semibold">Akaun</h1>
        <p class="mt-1 text-sm text-slate-400">Log masuk atau daftar akaun.</p>

        <div class="mt-5 grid grid-cols-2 gap-2 rounded-lg bg-slate-950/70 p-1">
            <button type="button" data-mode-btn="login" class="mode-btn rounded-md px-3 py-2 text-sm">Log Masuk</button>
            <button type="button" data-mode-btn="register" class="mode-btn rounded-md px-3 py-2 text-sm">Daftar</button>
        </div>

        <div id="login-panel" class="mt-5 space-y-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-login-role="donor" class="login-role-btn rounded-md border border-white/10 px-3 py-2 text-sm">Penderma</button>
                <button type="button" data-login-role="organizer" class="login-role-btn rounded-md border border-white/10 px-3 py-2 text-sm">Penganjur</button>
            </div>

            <a
                id="google-login-link"
                href="{{ route('auth.google.redirect', ['role' => $role]) }}"
                class="block w-full rounded-md border border-white/15 px-4 py-2 text-center text-sm text-slate-200 hover:bg-white/5"
            >
                Teruskan dengan Google
            </a>

            <form method="post" action="{{ route('auth.login.submit') }}" class="space-y-3">
                @csrf
                <input id="login-role-input" type="hidden" name="role" value="{{ old('role', $role) }}" />
                <div>
                    <label class="text-xs text-slate-400">Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Kata Laluan</label>
                    <input type="password" name="password" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <button type="submit" class="w-full rounded-md bg-emerald-400 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-emerald-300">
                    Log Masuk
                </button>
            </form>
        </div>

        <div id="register-panel" class="mt-5 hidden space-y-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-register-role="donor" class="register-role-btn rounded-md border border-white/10 px-3 py-2 text-sm">Penderma</button>
                <button type="button" data-register-role="organizer" class="register-role-btn rounded-md border border-white/10 px-3 py-2 text-sm">Penganjur</button>
            </div>

            <form method="post" action="{{ route('auth.register.submit') }}" class="space-y-3">
                @csrf
                <input id="register-role-input" type="hidden" name="role" value="{{ old('role', $role) }}" />
                <div>
                    <label class="text-xs text-slate-400">Nama Penuh</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Nama Samaran (pilihan)</label>
                    <input type="text" name="alias_name" value="{{ old('alias_name') }}" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Kata Laluan</label>
                    <input type="password" name="password" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Sahkan Kata Laluan</label>
                    <input type="password" name="password_confirmation" class="mt-1.5 w-full rounded-md border border-white/10 bg-slate-950 px-3 py-2 text-sm" required />
                </div>
                <button type="submit" class="w-full rounded-md bg-emerald-400 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-emerald-300">
                    Daftar Akaun
                </button>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const modeBtns = document.querySelectorAll('.mode-btn');
            const loginPanel = document.getElementById('login-panel');
            const registerPanel = document.getElementById('register-panel');
            const loginRoleBtns = document.querySelectorAll('.login-role-btn');
            const registerRoleBtns = document.querySelectorAll('.register-role-btn');
            const loginRoleInput = document.getElementById('login-role-input');
            const registerRoleInput = document.getElementById('register-role-input');
            const googleLoginLink = document.getElementById('google-login-link');

            const setMode = (mode) => {
                const isLogin = mode === 'login';
                loginPanel.classList.toggle('hidden', !isLogin);
                registerPanel.classList.toggle('hidden', isLogin);
                modeBtns.forEach((btn) => {
                    const active = btn.dataset.modeBtn === mode;
                    btn.classList.toggle('bg-emerald-400', active);
                    btn.classList.toggle('text-slate-900', active);
                    btn.classList.toggle('text-slate-300', !active);
                });
            };

            const setRoleButtons = (buttons, role) => {
                buttons.forEach((btn) => {
                    const active = (btn.dataset.loginRole || btn.dataset.registerRole) === role;
                    btn.classList.toggle('bg-emerald-400', active);
                    btn.classList.toggle('text-slate-900', active);
                    btn.classList.toggle('text-slate-300', !active);
                });
            };

            const setLoginRole = (role) => {
                loginRoleInput.value = role;
                googleLoginLink.href = `{{ route('auth.google.redirect') }}?role=${role}`;
                setRoleButtons(loginRoleBtns, role);
            };

            const setRegisterRole = (role) => {
                registerRoleInput.value = role;
                setRoleButtons(registerRoleBtns, role);
            };

            modeBtns.forEach((btn) => btn.addEventListener('click', () => setMode(btn.dataset.modeBtn)));
            loginRoleBtns.forEach((btn) => btn.addEventListener('click', () => setLoginRole(btn.dataset.loginRole)));
            registerRoleBtns.forEach((btn) => btn.addEventListener('click', () => setRegisterRole(btn.dataset.registerRole)));

            setMode(@json($mode));
            setLoginRole(loginRoleInput.value || 'donor');
            setRegisterRole(registerRoleInput.value || 'donor');
        })();
    </script>
</x-layouts.app>
