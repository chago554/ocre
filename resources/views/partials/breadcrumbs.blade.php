@if (count($breadcrumbs))
    <nav class="flex py-2 px-4 mb-6 dark:bg-white-200 border border-gray-100 rounded-md shadow-sm " aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs md:text-sm">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="inline-flex items-center">
                    @if ($breadcrumb->url && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="text-gray-600 hover:text-indigo-500 transition-colors duration-200 flex items-center">
                            @if($loop->first)
                                <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            @endif
                            {{ $breadcrumb->title }}
                        </a>
                        {{-- Separador tipo Flecha Sutil --}}
                        <svg class="w-4 h-4 ml-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    @else
                        <span class="ml-1 text-gray-600 font-semibold dark:text-gray-800">
                            {{ $breadcrumb->title }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif