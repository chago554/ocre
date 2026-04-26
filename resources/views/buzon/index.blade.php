<x-app-layout>
    <x-flash-message />

    <div class="bg-[#EBEEE9] rounded-[30px] shadow-[0px_10px_0px_rgba(0,0,0,0.05)] border border-gray-200 p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buzón de Soporte</h1>
            <p class="text-sm text-gray-500 mt-0.5">Mensajes enviados por los usuarios de la app.</p>
        </div>

        <div id="messages-table"></div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' };

    const IC = {
        eye: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>`,
        circleCheck: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>`,
        circleX: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>`,
        trash: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>`,
    };

    const LANG = { es: { pagination: {
        page_size: 'Filas', first: '«', prev: '‹', next: '›', last: '»', all: 'Todo',
        counter: { showing: 'Mostrando', of: 'de', rows: 'registros', pages: 'páginas' },
    }}};

    const table = new Tabulator('#messages-table', {
        ajaxURL: '{{ route('buzon.index') }}',
        ajaxConfig: { headers },
        pagination: true,
        paginationMode: 'remote',
        sortMode: 'remote',
        paginationSize: 15,
        paginationSizeSelector: [10, 15, 25],
        layout: 'fitColumns',
        locale: 'es',
        langs: LANG,
        placeholder: '<div class="flex flex-col items-center gap-3 text-gray-400 py-12"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4"><polyline points="22 13 16 13 16 19 8 19 8 13 2 13"/><path d="m22 13-10-9L2 13"/><line x1="12" x2="12" y1="22" y2="13"/></svg><p class="text-sm">No hay mensajes en el buzón.</p></div>',
        rowFormatter: (row) => {
            const data = row.getData();
            const el = row.getElement();
            if (!data.is_resolved) {
                el.style.borderLeft = '3px solid #fbbf24';
            } else {
                el.style.opacity = '0.7';
                el.style.borderLeft = '';
            }
        },
        columns: [
            {
                title: 'Usuario', field: 'user', minWidth: 160, headerSort: false,
                formatter: (cell) => {
                    const u = cell.getValue();
                    if (!u) return '—';
                    const name = `${u.name ?? ''} ${u.last_name ?? ''}`.trim();
                    return `<div class="flex items-center gap-2"><span class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center shrink-0 text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span><span class="font-medium text-gray-800 truncate">${name}</span></div>`;
                },
            },
            {
                title: 'Asunto', field: 'subject', minWidth: 180,
                formatter: (cell) => `<span class="text-gray-700 truncate block max-w-xs">${cell.getValue() ?? ''}</span>`,
            },
            {
                title: 'Estado', field: 'is_resolved', width: 120,
                formatter: (cell) => {
                    const resolved = !!cell.getValue();
                    if (resolved) return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Resuelto</span>`;
                    return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pendiente</span>`;
                },
            },
            {
                title: 'Recibido', field: 'created_at', width: 140,
                formatter: (cell) => `<span class="text-gray-400 text-xs">${new Date(cell.getValue()).toLocaleString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>`,
            },
            {
                title: 'Acciones', field: 'id', width: 115, headerSort: false,
                formatter: (cell) => {
                    const d = cell.getRow().getData();
                    const resolved = !!d.is_resolved;
                    return `<div class="flex items-center gap-1">
                        <button class="btn btn-ghost btn-xs rounded-lg text-gray-500" data-action="view" title="Ver">${IC.eye}</button>
                        <button class="btn btn-ghost btn-xs rounded-lg" data-action="toggle" title="${resolved ? 'Marcar pendiente' : 'Marcar resuelto'}">${resolved ? IC.circleX : IC.circleCheck}</button>
                        <button class="btn btn-ghost btn-xs rounded-lg text-error" data-action="delete" title="Eliminar">${IC.trash}</button>
                    </div>`;
                },
            },
        ],
    });

    document.getElementById('messages-table').addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const rowEl = btn.closest('.tabulator-row');
        if (!rowEl) return;
        const row = table.getRowFromElement(rowEl);
        const data = row.getData();

        if (btn.dataset.action === 'view') {
            window.location.href = `/buzon/${data.id}`;
        }

        if (btn.dataset.action === 'toggle') {
            try {
                const res = await axios.patch(`/buzon/${data.id}/resolve`, {}, { headers });
                row.update({ is_resolved: res.data.is_resolved });
                Toast.success(res.data.message);
            } catch { Toast.error('Error al actualizar el estado.'); }
        }

        if (btn.dataset.action === 'delete') {
            const result = await Modal.delete('¿Eliminar mensaje?', 'Esta acción no se puede deshacer.');
            if (!result.isConfirmed) return;
            try {
                await axios.delete(`/buzon/${data.id}`, { headers });
                row.delete();
                Toast.success('Mensaje eliminado correctamente.');
            } catch { Toast.error('Error al eliminar el mensaje.'); }
        }
    });
});
</script>
@endpush
</x-app-layout>
