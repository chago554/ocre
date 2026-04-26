<x-app-layout>
    {{ Breadcrumbs::render('verMensaje') }}

    <div class="bg-fondo rounded-[30px] shadow-[1px_2px_1px_rgba(0,0,0,0.3)] border border-gray-200 p-8 overflow-x-auto">
        <div class="flex items-center gap-4 mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Mensaje de Soporte</h1>
        </div>

        {{-- Estado y acción --}}
        <div class="flex items-center gap-4 mb-6">
            @if ($message->is_resolved)
                <span id="resolve-badge"
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-semibold rounded-full bg-green-300 text-green-700">
                    <x-lucide-check class="w-3.5 h-3.5" />Resuelto
                </span>
            @else
                <span id="resolve-badge"
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-semibold rounded-full bg-amber-100 text-amber-700">
                    <x-lucide-clock class="w-3.5 h-3.5" />Pendiente
                </span>
            @endif

            <button type="button" id="resolve-btn" onclick="toggleResolve({{ $message->id }})"
                class="btn btn-sm rounded text-sm {{ $message->is_resolved ? 'btn-ghost border border-gray-300 p-3' : 'bg-green-500 text-white border-none hover:bg-green-600 p-3' }}">
                <span
                    id="resolve-text">{{ $message->is_resolved ? 'Marcar como pendiente' : 'Marcar como resuelto' }}</span>
            </button>
        </div>

        {{-- Metadata --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class=" bg-gray-100 rounded-xl p-4 flex gap-3">
                <span class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                    <x-lucide-user class="w-4 h-4 text-primary" />
                </span>
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1 tracking-wide">Remitente</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $message->user->name }}
                        {{ $message->user->last_name }}</p>
                    <p class="text-gray-500 text-xs">{{ $message->user->email }}</p>
                </div>
            </div>
            <div class="bg-gray-100 rounded-xl p-4 flex gap-3">
                <span class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                    <x-lucide-calendar class="w-4 h-4 text-primary" />
                </span>
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1 tracking-wide">Recibido</p>
                    <p class="font-semibold text-gray-800 text-sm">{{ $message->created_at->format('d/m/Y') }}</p>
                    <p class="text-gray-500 text-xs">{{ $message->created_at->format('H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Asunto --}}
        <div class="mb-4">
            <p class="text-gray-400 text-sm font-bold uppercase mb-1 tracking-wide">Asunto</p>
            <h2 class="text-lg font-bold text-gray-800">{{ $message->subject }}</h2>
        </div>

        {{-- Cuerpo --}}
        <div class="bg-gray-100 rounded-xl p-6 mb-4">
            <div class="flex items-center gap-2 mb-3">
                <x-lucide-message-square class="w-4 h-4 text-gray-400" />
                <p class="text-gray-400 text-xs uppercase tracking-wide">Mensaje</p>
            </div>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">{{ $message->body }}</p>
        </div>

    </div>

    @push('scripts')
        <script>
            function toggleResolve(id_message) {

                let url = `{{ url('/buzon') }}/${id_message}/resolve`;
                axios.post(url, {
                    'id_message': id_message,
                    '_method': 'PATCH'
                }).then(async (response) => {
                    const data = await response.data;
                    const btn = document.getElementById('resolve-btn');
                    const badge = document.getElementById('resolve-badge');
                    const text = document.getElementById('resolve-text');

                    if (data.is_resolved == 1) {
                        badge.className =
                            'inline-flex items-center gap-1.5 px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700';
                        badge.innerHTML =
                            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>Resuelto';
                        btn.className = 'btn btn-sm rounded-xl btn-ghost border border-gray-300 p-3';
                        text.textContent = 'Marcar como pendiente';
                    } else {
                        badge.className =
                            'inline-flex items-center gap-1.5 px-3 py-1 text-sm font-semibold rounded-full bg-amber-100 text-amber-700';
                        badge.innerHTML =
                            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pendiente';
                        btn.className =
                            'btn btn-sm rounded-xl bg-green-500 text-white border-none hover:bg-green-600 p-3';
                        text.textContent = 'Marcar como resuelto';
                    }

                    Toast.success("Estado actualizado!");
                }).catch(error => {
                    Toast.error("Ha ocurrido un error!");
                });
            }
        </script>
    @endpush
</x-app-layout>
