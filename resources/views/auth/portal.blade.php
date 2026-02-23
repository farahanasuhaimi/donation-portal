<x-layouts.app :title="'Account'">
    <div class="mx-auto max-w-xl rounded-2xl border border-white/10 bg-white/5 p-8">
        <h1 class="text-2xl font-semibold">Account</h1>
        <p class="mt-2 text-sm text-slate-300">Login or create account, then choose role.</p>

        <div class="mt-6 flex rounded-lg border border-white/10 bg-slate-900/50 p-1">
            <button type="button" data-mode-btn="login" class="mode-btn flex-1 rounded-md px-3 py-2 text-sm font-medium">Login</button>
            <button type="button" data-mode-btn="register" class="mode-btn flex-1 rounded-md px-3 py-2 text-sm font-medium">Create Account</button>
        </div>

        <div id="login-panel" class="mt-5 space-y-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-login-role="donor" class="login-role-btn rounded-lg border border-white/10 px-3 py-2 text-sm">Donor</button>
                <button type="button" data-login-role="organizer" class="login-role-btn rounded-lg border border-white/10 px-3 py-2 text-sm">Organizer</button>
            </div>

            <a
                id="google-login-link"
                href="{{ route('auth.google.redirect', ['role' => $role]) }}"
                class="block w-full rounded-lg border border-white/20 bg-white/10 px-4 py-2 text-center text-sm font-semibold hover:bg-white/20"
            >
                Continue with Google
            </a>

            <form method="post" action="{{ route('auth.login.submit') }}" class="space-y-4">
                @csrf
                <input id="login-role-input" type="hidden" name="role" value="{{ old('role', $role) }}" />
                <div>
                    <label class="text-xs text-slate-400">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Password</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <button type="submit" class="w-full rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300">
                    Sign In
                </button>
            </form>
        </div>

        <div id="register-panel" class="mt-5 hidden space-y-4">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-register-role="donor" class="register-role-btn rounded-lg border border-white/10 px-3 py-2 text-sm">Donor</button>
                <button type="button" data-register-role="organizer" class="register-role-btn rounded-lg border border-white/10 px-3 py-2 text-sm">Organizer</button>
            </div>

            <form method="post" action="{{ route('auth.register.submit') }}" class="space-y-4">
                @csrf
                <input id="register-role-input" type="hidden" name="role" value="{{ old('role', $role) }}" />
                <div>
                    <label class="text-xs text-slate-400">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Nama Samaran (optional)</label>
                    <input type="text" name="alias_name" value="{{ old('alias_name') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Password</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <div>
                    <label class="text-xs text-slate-400">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm" required />
                </div>
                <button type="submit" class="w-full rounded-lg bg-emerald-400 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-slate-900 hover:bg-emerald-300">
                    Create Account
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
