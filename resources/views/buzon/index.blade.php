<x-app-layout>
    {{ Breadcrumbs::render('buzon') }}

    <div
        class="bg-fondo rounded-[30px] shadow-[1px_2px_1px_rgba(0,0,0,0.3)]  border border-gray-200 p-8 overflow-x-auto">
        <div class="flex items-center justify-between mb-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Buzón de Soporte</h1>
                <p class="text-sm text-gray-500 mt-0.5">Mensajes enviados por los usuarios de la app.</p>
            </div>
        </div>

        <div id="buzon-table" style="min-width:0;width:100%"></div>
    </div>


    @push('scripts')
        <script>
            let table;

            document.addEventListener('DOMContentLoaded', async function() {

                const LANG = {
                    es: {
                        pagination: {
                            page_size: 'Filas',
                            first: '«',
                            prev: '‹',
                            next: '›',
                            last: '»',
                            all: 'Todo',
                            counter: {
                                showing: 'Mostrando',
                                of: 'de',
                                rows: 'registros',
                                pages: 'páginas'
                            },
                        }
                    }
                };

                let url = `{{ route('buzon.get-buzon') }}`;
                table = new Tabulator("#buzon-table", {
                    ajaxURL: url,
                    ajaxParams: {
                        page: 1
                    },
                    pagination: true,
                    paginationMode: "remote",
                    sortMode: "remote",
                    paginationSize: 10,
                    paginationSizeSelector: [5, 10, 15, 25],
                    layout: 'fitColumns',

                    locale: 'es',
                    langs: LANG,

                    ajaxResponse: function(url, params, response) {
                        return {
                            last_page: response.last_page,
                            data: response.data,
                        };
                    },

                    columns: [{
                            title: "Recibido",
                            field: "created_at",
                            width: 150,
                            headerSort: true,
                            formatter: function(cell) {
                                const value = cell.getValue();
                                if (!value) return "";
                                const date = new Date(value);
                                return new Intl.DateTimeFormat('es-MX', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                }).format(date);
                            }
                        },
                        {
                            title: "Usuario",
                            field: "user",
                            width: 300,
                            headerSort: false,
                            formatter: (cell) => {
                                const u = cell.getValue();
                                if (!u) return '—';
                                const name = `${u.name ?? ''} ${u.last_name ?? ''}`.trim();
                                return `<div class="flex items-center gap-2"><span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center shrink-0 text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span><span class="font-medium text-gray-800 truncate">${name}</span></div>`;
                            },
                        },
                        {
                            title: 'Asunto',
                            field: 'subject',
                            minWidth: 180,
                            headerSort: false,
                            formatter: (cell) =>
                                `<span class="text-gray-700 truncate block max-w-xs">${cell.getValue() ?? ''}</span>`,
                        },
                        {
                            title: 'Estado',
                            field: 'is_resolved',
                            width: 120,
                            formatter: (cell) => {
                                const resolved = !!cell.getValue();
                                if (resolved)
                                    return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Resuelto</span>`;
                                return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pendiente</span>`;
                            },
                        },
                        {
                            title: "Acciones",
                            field: "id",
                            headerSort: false,
                            hozAlign: 'center',
                            width: 100,

                            formatter: function(cell) {
                                const id = cell.getValue();
                                const d = cell.getRow().getData();
                                const resolved = !!d.is_resolved;

                                return `
                                    <div class="dropdown dropdown-left" >
                                        <div style=" background:rgb(217, 217, 217)" tabindex="0" role="button" class="btn m-1"> <x-lucide-settings class="w-5 h-5" /> </div>
                                        <ul tabindex="-1" class="dropdown-content menu rounded-box w-52 p-2 shadow-sm" style=" background: rgb(255, 255, 255)">
                                            <li><a href="javascript:void(0)" onclick="ver(${id})" title="Ver">Ver</a></li>
                                            <li><a href="javascript:void(0)" onclick="toogleResolve(${id})" title="${resolved ? 'Marcar pendiente' : 'Marcar resuelto'}"> ${resolved ? 'Marcar pendiente' : 'Marcar resuelto'} </a></li>
                                            <li><a href="javascript:void(0)" onclick="remove(${id})" title="Eliminar">Eliminar</a></li>
                                        </ul>
                                    </div>
                                `;
                            }
                        },
                    ],
                });
            });

            function ver(id_message) {
                window.location.href = `{{ url('/buzon') }}/${id_message}`;
            }

            function toogleResolve(id_message) {
                let url = `{{ url('/buzon') }}/${id_message}/resolve`;
                axios.post(url, {
                    'id_message': id_message,
                    '_method': 'PATCH'
                }).then(async (response) => {
                    table.replaceData();
                    Toast.success("Estado actualizado!");
                }).catch(error => {
                    Toast.error("Ha ocurrido un error!");
                });
            }

            function remove(id_message) {
                Modal.delete('¿Eliminar mensaje?', 'Esta acción no se puede deshacer.').then(result => {
                    if (!result.isConfirmed) return;

                    axios.delete(`{{ url('/buzon') }}/${id_message}`).then(async (response) => {
                        Toast.success('Message eliminado correctamente.');
                        table.replaceData();
                    }).catch(error => {
                        Toast.error("Ha ocurrido un error!");
                    });
                });
            }
        </script>
    @endpush


</x-app-layout>
