<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6  space-y-6">
            <div
                class="p-4 sm:p-8 bg-white transition-all duration-300 ease-out 
                shadow-[1px_2px_1px_rgba(0,0,0,0.1)] border active:translate-y-[4px]  sm:rounded-lg">
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-4 sm:p-8 bg-white transition-all duration-300 ease-out 
                shadow-[2px_2px_2px_rgba(0,0,0,0.1)] border active:translate-y-[4px]  sm:rounded-lg">
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div 
                class="p-4 sm:p-8 bg-white transition-all duration-300 ease-out 
                shadow-[2px_2px_2px_rgba(0,0,0,0.1)] border active:translate-y-[4px]  sm:rounded-lg">
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
