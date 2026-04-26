<x-app-layout>

    {{ Breadcrumbs::render('posts') }}

    <div
        class="bg-fondo rounded-[30px] shadow-[1px_2px_1px_rgba(0,0,0,0.3)]  border border-gray-200 p-8 overflow-x-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Biblioteca</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestiona los artículos publicados en la app.</p>
            </div>
            <a href="{{ route('biblioteca.create') }}"
                class="btn bg-ocre hover:bg-primary/90 text-white rounded-xl border-none px-6 gap-2">
                <x-lucide-plus class="w-4 h-4" /> Nuevo artículo
            </a>
        </div>

        <div id="posts-table" style="min-width:0;width:100%"></div>
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

                let url = `{{ route('biblioteca.get-posts') }}`;
                table = new Tabulator("#posts-table", {
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
                            title: "Última modificacion",
                            field: "updated_at",
                            width: 150,
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
                            title: "Título",
                            field: "title",
                            width: 500,
                        },
                        {
                            title: "Categorías",
                            field: "category",
                            headerSort: false,
                            formatter: function(cell) {
                                const cats = JSON.parse(cell.getValue());
                                return cats.map(c =>
                                    `<span class="badge bg-light me-1">${c}</span>`).join(
                                    '');
                            }
                        },
                        {
                            title: "Lectura",
                            field: "read_time",
                            formatter: function(cell) {
                                return `${cell.getValue()} min`;
                            }
                        },
                        {
                            title: "Estado",
                            field: "is_published",
                            formatter: function(cell) {
                                return cell.getValue() ?
                                    `<span class="badge bg-success">Publicado</span>` :
                                    `<span class="badge bg-warning text-dark">Borrador</span>`;
                            }
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
                                const pub = !!d.is_published;

                                return `
                                    <div class="dropdown dropdown-left" >
                                        <div style=" background:rgb(217, 217, 217)" tabindex="0" role="button" class="btn m-1"> <x-lucide-settings class="w-5 h-5" /> </div>
                                        <ul tabindex="-1" class="dropdown-content menu rounded-box w-52 p-2 shadow-sm" style=" background: rgb(255, 255, 255)">
                                            <li><a href="javascript:void(0)" onclick="tooglePublish(${id})" title="${pub ? 'Despublicar' : 'Publicar'}"> ${pub ? 'Despublicar' : 'Publicar'} </a></li>
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


            function tooglePublish(id_post) {
                let url = `{{ url('/biblioteca') }}/${id_post}/toggle-publish`;
                axios.post(url, {
                    'id_post': id_post,
                    '_method': 'PATCH'
                }).then(async (response) => {
                    table.replaceData();
                    Toast.success("Estado actualizado!");
                }).catch(error => {
                    Toast.error("Ha ocurrido un error!");
                });
            }

            function edit(id_post) {
                window.location.href = `{{ url('/biblioteca') }}/${id_post}/edit`;
            }

            function remove(id_post) {
                Modal.delete('¿Eliminar post?', 'El post se enviará a la papelera.').then(result => {
                    if (!result.isConfirmed) return;

                    axios.delete(`{{ url('/biblioteca') }}/${id_post}`).then(async (response) => {
                        Toast.success('Artículo eliminado correctamente.');
                        table.replaceData();
                    }).catch(error => {
                        Toast.error("Ha ocurrido un error!");
                    });
                });
            }
        </script>
    @endpush


</x-app-layout>
