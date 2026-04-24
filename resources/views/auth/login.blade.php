<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold tracking-tight">Inicia sesión</h2>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="form-control">
            <x-input-label for="email" :value="__('Email')" />


            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')"
                required autofocus autocomplete="email" placeholder="email@ejemplo.com" />
        </div>

        <div class="form-control">
            <x-input-label for="name" :value="__('Password')" />

            <div class="relative">

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" :value="old('password')"
                    required autocomplete="password" placeholder="********" />

                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">

                    <button type="button" id="togglePassword"
                        class="absolute right-3 flex items-center text-info hover:text-primary transition-colors focus:outline-none">
                        {{-- Icono Ojo (Por defecto) --}}
                        <x-lucide-eye id="eyeIcon" class="w-5 h-5" />
                        {{-- Icono Ojo Tachado (Oculto inicialmente) --}}
                        <x-lucide-eye-off id="eyeOffIcon" class="w-5 h-5 hidden" />
                    </button>
                </span>
            </div>

            <div class="text-right mt-2">
                <a href="{{ route('password.request') }}"
                    class="text-xs text-secondary font-medium hover:underline">Olvidé mi contraseña</a>
            </div>
        </div>

        <div class="pt-4">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <p class="text-center text-xs font-medium text-black mt-4">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-secondary hover:underline">Regístrate</a>
        </p>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            toggleButton.addEventListener('click', function() {
                // Cambiar tipo de input
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';

                // Alternar iconos
                if (isPassword) {
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        });
    </script>
</x-guest-layout>
