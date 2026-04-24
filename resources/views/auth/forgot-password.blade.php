<x-guest-layout>
    <div class="text-center mb-5">
        <h2 class="text-3xl font-bold tracking-tight">Recuperar contraseña</h2>
    </div>

    <div class="mb-10 text-center">
        Introduce tu correo para recibir las instrucciones de recuperación
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-4 ">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block text- mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus placeholder="Ingresa tu correo electrónico"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-10">
            <x-primary-button class="p-10">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>

        <p class="text-center text-xs font-medium text-black mt-4">
            <a href="{{ route('login') }}" class="text-secondary hover:underline">Volver al inicio de sesión</a>
        </p>
    </form>
</x-guest-layout>
