<x-app-layout>
    <x-flash-message />

    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <x-stats-card id="count-simulaciones" title="Simulaciones Creadas" icon="zap" />
            <x-stats-card id="count-usuarios" title="Cuentas Nuevas" icon="users" />
            <x-stats-card id="count-posts" title="Artículos Publicados" icon="file-text" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-h-7">
            <div
                class="lg:col-span-8 bg-fondo p-8 rounded-3xl shadow-[1px_2px_1px_rgba(0,0,0,0.3)] border border-gray-400">
                <h4 class="text-xl text-center text-gray-700 mb-6 font-medium">Interacción diaria</h4>
                <div class="relative h-80">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div
                class="lg:col-span-4 bg-fondo p-8 rounded-3xl shadow-[1px_2px_1px_rgba(0,0,0,0.3)] border border-gray-400 text-gray-800">
                <h4 class="text-xl mb-6 font-medium">Últimos Usuarios Registrados</h4>
                <div id="users-table"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', async function() {

                // Stats
                axios.get(`{{ route('get-stats') }}`).then((response) => {
                    const data = response.data.data;
                    document.querySelector('#count-simulaciones h3').textContent = data.simulations
                        .toLocaleString();
                    document.querySelector('#count-usuarios h3').textContent = data.users.toLocaleString();
                    document.querySelector('#count-posts h3').textContent = data.posts.toLocaleString();
                }).catch(error => {
                    console.error('Error cargando stats:', error);
                });

                // Gráfica de interacción diaria
                axios.get('{{ route('get-daily.interactions') }}').then(async (response) => {

                    const data = await response.data.data;

                    const ctx = document.getElementById('dailyChart').getContext('2d');
                    let delayed;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: data.datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                        }
                    });
                }).catch(error => {
                    console.error('Error cargando grafica:', error);
                });

                // Últimos usuarios registrados
                axios.get(`{{ route('last-users-registers') }}`).then((response) => {
                    const data = response.data.data;
                    new Tabulator('#users-table', {
                        data: data,
                        layout: 'fitColumns',
                        placeholder: '<p class="text-sm text-gray-400 py-4 text-center">Sin registros este mes.</p>',
                        columns: [{
                                title: 'Nombre',
                                field: 'name',
                                minWidth: 120,
                                formatter: (cell) => {
                                    const d = cell.getRow().getData();
                                    return `<span class="font-medium text-gray-800">${d.name ?? ''} ${d.last_name ?? ''}</span>`;
                                },
                            },
                            {
                                title: 'Registro',
                                field: 'created_at',
                                width: 110,
                                formatter: (cell) =>
                                    `<span class="text-gray-400 text-xs">${new Date(cell.getValue()).toLocaleDateString('es-MX')}</span>`,
                            },
                        ],
                    });
                }).catch(error => {
                    console.error('Error cargando usuarios:', error);
                });

            });
        </script>
    @endpush
</x-app-layout>
