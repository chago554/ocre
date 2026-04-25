<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">
            {{ __('Actualizar contraseña') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Usa una contraseña larga y aleatoria para mantener tu cuenta segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="text-left" x-data="{ show: false }">
            <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_current_password" name="current_password" :type="'password'"
                    x-bind:type="show ? 'text' : 'password'" class="block w-full pr-10" autocomplete="current-password"
                    placeholder="Ingresa tu contraseña actual" />
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-lucide-eye x-show="!show" class="w-4 h-4" />
                    <x-lucide-eye-off x-show="show" class="w-4 h-4" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="text-left" x-data="{ show: false }">
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" x-bind:type="show ? 'text' : 'password'"
                    class="block w-full pr-10" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-lucide-eye x-show="!show" class="w-4 h-4" />
                    <x-lucide-eye-off x-show="show" class="w-4 h-4" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div class="text-left" x-data="{ show: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                    x-bind:type="show ? 'text' : 'password'" class="block w-full pr-10" autocomplete="new-password"
                    placeholder="Repite la nueva contraseña" />
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-lucide-eye x-show="!show" class="w-4 h-4" />
                    <x-lucide-eye-off x-show="show" class="w-4 h-4" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex flex-col items-center gap-3 pt-2">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Toast.success("{{ __('Contraseña actualizada.') }}");
                    });
                </script>
            @endif
        </div>
    </form>
</section>
