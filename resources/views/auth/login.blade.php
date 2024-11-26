<x-guest-layout>
    @vite(['resources/css/login.css'])


    <!-- Sessão de Status -->
    <div class="form-container">
        <div class="form-header">
            Faça seu Login
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="'Email'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Senha -->
            <div class="mt-4">
                <x-input-label for="password" :value="'Senha'" />

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Lembrar de mim -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Lembrar de mim</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4 form-footer">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    Entrar
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>