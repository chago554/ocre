<nav :class="open ? 'w-64' : 'w-20'"
    class="flex flex-col h-screen bg-light border-r border-gray-300 fixed left-0 top-0 text-gray-700 transition-all duration-300 ease-in-out z-50">

    <button @click="open = !open"
        class="absolute -right-3 top-10 bg-light border border-gray-300 rounded-full p-1 hover:text-orange-500 transition shadow-sm">
        <x-lucide-chevron-left x-show="open" class="w-4 h-4" />
        <x-lucide-chevron-right x-show="!open" class="w-4 h-4" />
    </button>

    <div class="flex items-center h-20 px-4 overflow-hidden transition-all duration-300"
        :class="open ? 'justify-start px-6' : 'justify-center px-0'">

        <a href="{{ route('dashboard') }}" class="flex items-center min-w-max">
            <div x-show="open" x-transition.opacity.duration.300ms>
                <img src="{{ asset('logo.png') }}" alt="Logo OCRE" class="h-12 w-auto object-contain">
            </div>

            <div x-show="!open" x-transition.opacity.duration.300ms>
                <span class="text-xl font-semibold text-[#C5A059] tracking-tighter">
                    OCRE
                </span>
            </div>
        </a>
    </div>

    <div class="flex flex-col items-center py-6 border-b border-gray-300 overflow-hidden">
        <div class="relative shrink-0">
            <img :class="open ? 'h-20 w-20' : 'h-10 w-10'"
                class="rounded-full object-cover border-2 border-green-500 transition-all duration-300"
                src="{{ 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=EBF4FF&color=7F9CF5' }}"
                alt="{{ Auth::user()->name }}">
        </div>
        <div x-show="open" x-transition.opacity class="mt-3 text-center whitespace-nowrap">
            <h2 class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</h2>
            <a href="{{ route('profile.edit') }}" class="text-sm  text-orange-500 hover:underline"> Editar perfil </a>
        </div>
    </div>

    <div class="flex-grow mt-6 px-3 space-y-2 overflow-hidden">

        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="layout-dashboard">
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('biblioteca.index')" :active="request()->routeIs('biblioteca.*')" icon="book-open">
            Biblioteca
        </x-nav-link>

        <x-nav-link :href="route('tasas.index')" :active="request()->routeIs('tasas.*')" icon="trending-up">
            Tasas de inversión
        </x-nav-link>

        <x-nav-link :href="route('buzon.index')" :active="request()->routeIs('buzon.*')" icon="mail">
            Buzón
        </x-nav-link>
    </div>

    <div class="p-3 border-t border-gray-300 overflow-hidden">

        <form id="form-logout" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirLogout()"
                class="flex items-center gap-4 w-full px-3 py-2 text-gray-600 hover:text-red-600 transition-colors font-medium">
                <x-lucide-log-out class="w-6 h-6 shrink-0" />
                <span x-show="open" x-transition.opacity>{{ __('Salir') }}</span>
            </button>
        </form>
    </div>
</nav>

<script>
    function confirLogout() {

        Modal.confirm(
                '¿Cerrar sesión?',
                'Se cerrará tu sesión actual.'
            )
            .then(result => {
                if (result.isConfirmed) {
                    document.getElementById('form-logout').submit();
                }
            });
    }
</script>
