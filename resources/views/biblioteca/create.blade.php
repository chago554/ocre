<x-app-layout>

    {{ Breadcrumbs::render('crearPost') }}

    <div class="bg-fondo rounded-[30px] shadow-[1px_2px_1px_rgba(0,0,0,0.3)] border border-gray-200 p-8 overflow-x-auto">
        <div class="flex items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Nuevo Post</h1>
                <p class="text-sm text-gray-500">Completa los campos para crear un nuevo post.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('biblioteca.store') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="title" value="Título" />
                <x-text-input id="title" name="title" class="mt-1 w-full" value="{{ old('title') }}" required
                    placeholder="Ingrese el titulo" />
                <x-input-error :messages="$errors->get('title')" class="mt-1" />
            </div>

            <div x-data="{ preview: '{{ old('image_url') }}' }">
                <x-input-label for="image_url" value="URL de la imagen" />
                <x-text-input id="image_url" name="image_url" type="url" class="mt-1 w-full"
                    value="{{ old('image_url') }}" placeholder="https://..."
                    x-on:input="preview = $event.target.value" />
                <x-input-error :messages="$errors->get('image_url')" class="mt-1" />
                <div x-show="preview" x-cloak class="mt-3">
                    <img :src="preview" x-on:error="preview = ''"
                        class="h-40 w-full object-cover rounded-xl border border-gray-200 bg-gray-100"
                        alt="Vista previa">
                </div>
            </div>

            <div>
                <x-input-label for="read_time" value="Tiempo de lectura (minutos)" />
                <x-text-input id="read_time" name="read_time" type="number" min="1" class="mt-1 w-32"
                    value="{{ old('read_time', 5) }}" required />
                <x-input-error :messages="$errors->get('read_time')" class="mt-1" />
            </div>

            <div>
                <x-input-label value="Categorías" />
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach (['Inversión', 'Educación', 'Noticias', 'Renta Fija', 'Renta Variable', 'Crypto', 'Finanzas Personales', 'Otra'] as $cat)
                        <label class="cursor-pointer select-none">
                            <input type="checkbox" name="categories[]" value="{{ $cat }}" class="sr-only peer"
                                {{ in_array($cat, old('categories', [])) ? 'checked' : '' }}>
                            <span
                                class="inline-block px-3 py-1.5 text-sm rounded-full border border-gray-300
                                         text-gray-600 bg-white hover:border-primary/60 transition-colors
                                         peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary">
                                {{ $cat }}
                            </span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('categories')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="content" value="Contenido" />
                <textarea id="content" name="content" rows="14"
                    class="mt-1 w-full bg-[#E9EEEA] rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary/50 focus:border-transparent text-gray-700 p-4 text-md resize-none"
                    placeholder="Escribe el contenido del post aquí..." required>{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-1" />
            </div>

            <div class="flex items-center gap-3 p-4 bg-white/50 rounded-xl border border-gray-200">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                    class="checkbox checkbox-primary" {{ old('is_published') ? 'checked' : '' }}>
                <div>
                    <label for="is_published" class="text-sm font-medium text-gray-700 cursor-pointer">Publicar
                        inmediatamente</label>
                    <p class="text-sm text-gray-400">El post será visible en la app al guardarlo.</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2 w-full justify-end">
                <div class="">
                    <x-primary-button class="p-3">
                        <x-lucide-save class="w-4 h-4 mr-1" /> Crear Post
                    </x-primary-button>
                </div>

                <div>
                    <a href="{{ route('biblioteca.index') }}" class="btn btn-ghost p-3 rounded-xl">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
