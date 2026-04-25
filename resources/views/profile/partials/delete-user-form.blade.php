<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-gray-800">
            {{ __('Eliminar cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button onclick="Modal.deleteAccount('{{ route('profile.destroy') }}', '{{ csrf_token() }}')">
        {{ __('Eliminar cuenta') }}
    </x-danger-button>
</section>
