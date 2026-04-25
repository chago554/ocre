<x-app-layout>
    <x-flash-message />

    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <x-stats-card id="count-simulaciones" title="Simulaciones Creadas" icon="zap" />
            <x-stats-card id="count-usuarios" title="Usuarios Activos" icon="users" />
            <x-stats-card id="count-articulos" title="Artículos Publicados" icon="file-text" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-4 bg-[#EBEEE9] p-8 rounded-[30px] shadow-[0px_10px_0px_rgba(0,0,0,0.05)] border border-gray-200">
                <h4 class="text-xl text-center text-gray-700 mb-6 font-medium">Interacción diaria</h4>
                <div class="relative h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div class="lg:col-span-8 bg-[#EBEEE9] p-8 rounded-[30px] shadow-[0px_10px_0px_rgba(0,0,0,0.05)] border border-gray-200 text-gray-800">
                <h4 class="text-xl mb-6 font-medium">Últimos Usuarios Registrados</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-gray-300 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="pb-3">Nombre</th>
                                <th class="pb-3">Email</th>
                                <th class="pb-3">Registro</th>
                            </tr>
                        </thead>
                        <tbody id="table-users-body">
                            <tr><td colspan="3" class="py-4 text-center text-gray-400 text-sm">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', async function () {

        // Stats
        try {
            const { data: res } = await axios.get('{{ route("get-stats") }}');
            document.querySelector('#count-simulaciones') = res.data.simulations.toLocaleString();
            document.querySelector('#count-usuarios')     = res.data.users.toLocaleString();
            document.querySelector('#count-articulos')    = res.data.posts.toLocaleString();
        } catch (e) {
            console.error('Error cargando stats:', e);
        }

        // Gráfica de interacción diaria
        try {
            const { data: res } = await axios.get('{{ route("get-daily.interactions") }}');
            const ctx = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: { labels: res.data.labels, datasets: res.data.datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        } catch (e) {
            console.error('Error cargando gráfica:', e);
        }

        // Últimos usuarios
        try {
            const { data: res } = await axios.get('{{ route("last-users-registers") }}');
            const tbody = document.getElementById('table-users-body');
            if (!res.data.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="py-4 text-center text-gray-400 text-sm">Sin registros este mes.</td></tr>';
                return;
            }
            tbody.innerHTML = res.data.map(u => `
                <tr class="border-b border-gray-200 hover:bg-white/50 text-sm">
                    <td class="py-2 font-medium">${u.name} ${u.last_name}</td>
                    <td class="py-2 text-gray-500">${u.email}</td>
                    <td class="py-2 text-gray-400">${new Date(u.created_at).toLocaleDateString('es-MX')}</td>
                </tr>`).join('');
        } catch (e) {
            console.error('Error cargando usuarios:', e);
        }

    });
    </script>
    @endpush
</x-app-layout>
