<x-app-layout>
    <div
        class="bg-fondo rounded-[30px] shadow-[1px_2px_1px_rgba(0,0,0,0.3)]  border border-gray-200 p-8 overflow-x-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tasas de Inversión</h1>
                <p class="text-sm text-gray-500 mt-0.5">Administra las tasas anuales por instrumento.</p>
            </div>

            <button class="btn bg-ocre hover:bg-primary/90 text-white rounded-xl border-none px-6 gap-2"
                onclick="nueva_tasa_modal.showModal(); document.getElementById('crear_tasa_form').reset();"> <x-lucide-plus class="w-4 h-4" /> Nueva tasa</button>

        </div>

        {{-- Modal: Crear tasa --}}
        <dialog id="nueva_tasa_modal" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>

                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <x-lucide-trending-up class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-bold text-gray-800">Nueva Tasa</h3>
                </div>

                <form id="crear_tasa_form" method="POST">
                    @csrf

                    <div>
                        <x-input-label value="Instrumento" />
                        <x-text-input class="mt-1 w-full" id="instrument_name" placeholder="ej. Bonos del Tesoro" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label value="Tasa Anual (%)" />
                        <div class="relative mt-1">
                            <x-text-input id="annual_rate" type="number" step="0.01" min="0" max="999.99" class="w-full pr-8" placeholder="0.00" required />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                    </div>

                    <div class="modal-action flex items-center gap-3">
                        <button type="button" class="btn" onclick="this.closest('dialog').close()">Cancelar</button>

                        <button type="button" onclick="create();" class="btn rounded-xl border-none px-6 text-white"
                            style="background: #E6AD56">Guardar</button>
                    </div>
                </form>
            </div>
        </dialog>

        {{-- Modal: Editar tasa --}}
        <dialog id="editar_tasa_modal" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>

                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <x-lucide-trending-up class="w-5 h-5" />
                    </span>
                    <h3 class="text-xl font-bold text-gray-800">Editar Tasa</h3>
                </div>

                <form id="editar_tasa_form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id_tasa">

                    <div>
                        <x-input-label value="Instrumento" />
                        <x-text-input class="mt-1 w-full" id="instrument_name_edit" placeholder="ej. Bonos del Tesoro" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label value="Tasa Anual (%)" />
                        <div class="relative mt-1">
                            <x-text-input id="annual_rate_edit" type="number" step="0.01" min="0" max="999.99" class="w-full pr-8" placeholder="0.00" required />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                    </div>

                    <div class="modal-action flex items-center gap-3">
                        <button type="button" class="btn" onclick="this.closest('dialog').close()">Cancelar</button>

                        <button type="button" onclick="update();" class="btn rounded-xl border-none px-6 text-white"
                            style="background: #E6AD56">Actualizar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <div id="rates-table" style="min-width:0;width:100%"></div>
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

                let url = `{{ route('tasas.get-rates') }}`;
                table = new Tabulator("#rates-table", {
                    ajaxURL: url,
                    ajaxParams: {
                        page: 1
                    },
                    pagination: true,
                    paginationMode: "remote",
                    sortMode: "remote",
                    paginationSize: 5,
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
                            title: "Última modificacion",
                            field: "updated_at",
                            width: 300,
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
                            title: "Instrumento",
                            field: "instrument_name",
                            minWidth: 200,
                        },
                        {
                            title: "Taza anual",
                            field: "annual_rate",
                            width: 200,
                            formatter: (cell) =>
                                `<span class="font-mono text-lg font-bold ">
                                    ${parseFloat(cell.getValue()).toFixed(2)}
                                    <span class="font-mono text-lg font-bold">%</span>
                                </span>`,
                        },
                        {
                            title: "Acciones",
                            field: "id",
                            headerSort: false,
                            hozAlign: 'center',
                            width: 100,

                            formatter: function(cell) {
                                const id = cell.getValue();

                                return `
                                    <div class="dropdown dropdown-left" >
                                        <div style=" background:rgb(217, 217, 217)" tabindex="0" role="button" class="btn m-1"> <x-lucide-settings class="w-5 h-5" /> </div>
                                        <ul tabindex="-1" class="dropdown-content menu rounded-box w-52 p-2 shadow-sm" style=" background: rgb(255, 255, 255)">
                                            <li><a href="javascript:void(0)" onclick="edit(${id})" title="Editar esta publicación">Editar</a></li>
                                            <li><a href="javascript:void(0)" onclick="remove(${id})" title="Eliminar esta publicación">Eliminar</a></li>
                                        </ul>
                                    </div>
                                `;
                            }
                        },
                    ],
                });
            });

            function create() {
                const data = {
                    instrument_name: document.getElementById('instrument_name').value,
                    annual_rate: document.getElementById('annual_rate').value,
                };

                axios.post(`{{ url('/tasas') }}`, data).then(async (response) => {
                        Toast.success("Tasa de inversion creada!");
                        nueva_tasa_modal.close();
                        table.replaceData();
                    }).catch(error => {
                        Toast.error("Ha ocurrido un error!");
                    });
            }

            function edit(id_tasa) {
                let url = `{{ url('/tasas') }}/${id_tasa}`;

                axios.get(url).then(async (response) => {
                    document.getElementById('id_tasa').value = response.data.id;
                    document.getElementById('instrument_name_edit').value = response.data.instrument_name;
                    document.getElementById('annual_rate_edit').value = response.data.annual_rate;
                    editar_tasa_modal.showModal();
                }).catch(error => {
                    Toast.error('Ops, ocurrio un error!');
                    console.log(error);
                });
            }

            function update() {
                const data = {
                    _method: "PUT",
                    id: document.getElementById('id_tasa').value,
                    instrument_name: document.getElementById('instrument_name_edit').value,
                    annual_rate: document.getElementById('annual_rate_edit').value,
                };

                axios.post(`{{ url('/tasas') }}`, data).then(async (response) => {
                    Toast.success("Tasa de inversion actualizada!");
                    editar_tasa_modal.close();
                    table.replaceData();
                }).catch(error => {
                    Toast.error("Ha ocurrido un error!");
                });
            }

            function remove(id_tasa) {
                Modal.delete('¿Eliminar tasa de inversion?', 'La taza de inversion se enviará a la papelera.').then(result => {
                    if (!result.isConfirmed) return;

                    axios.delete(`{{ url('/tasas') }}/${id_tasa}`).then(async (response) => {
                        Toast.success('Registro eliminado correctamente.');
                        table.replaceData();
                    }).catch(error => {
                        Toast.error("Ha ocurrido un error!");
                    });
                });
            }
        </script>
    @endpush
</x-app-layout>
