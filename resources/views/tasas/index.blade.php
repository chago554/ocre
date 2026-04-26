<x-app-layout>
    <x-flash-message />

    <div x-data="ratesPage()"
         class="bg-[#EBEEE9] rounded-[30px] shadow-[0px_10px_0px_rgba(0,0,0,0.05)] border border-gray-200 p-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tasas de Inversión</h1>
                <p class="text-sm text-gray-500 mt-0.5">Administra las tasas anuales por instrumento.</p>
            </div>
            <button @click="showCreate = true"
                    class="btn bg-primary hover:bg-primary/90 text-white rounded-xl border-none px-6 gap-2">
                <x-lucide-plus class="w-4 h-4" /> Nueva tasa
            </button>
        </div>

        <div id="rates-table"></div>

        {{-- Modal: Crear --}}
        <div x-show="showCreate" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div @click.outside="showCreate = false"
                 class="bg-white rounded-[30px] p-8 w-full max-w-md shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <x-lucide-plus class="w-5 h-5 text-primary" />
                    </span>
                    <h3 class="text-xl font-bold text-gray-800">Nueva Tasa</h3>
                </div>
                <form @submit.prevent="handleCreate" class="space-y-4">
                    <div>
                        <x-input-label value="Instrumento" />
                        <x-text-input x-model="form.instrument_name" class="mt-1 w-full"
                                      placeholder="ej. Bonos del Tesoro" required />
                    </div>
                    <div>
                        <x-input-label value="Tasa Anual (%)" />
                        <div class="relative mt-1">
                            <x-text-input x-model="form.annual_rate" type="number"
                                          step="0.01" min="0" max="999.99" class="w-full pr-8"
                                          placeholder="0.00" required />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showCreate = false" class="btn btn-ghost rounded-xl">Cancelar</button>
                        <button type="submit" class="btn bg-primary text-white rounded-xl border-none px-6"
                                :disabled="loading" :class="{ 'loading': loading }">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Editar --}}
        <div x-show="showEdit" x-cloak
             @open-edit-rate.window="openEdit($event.detail)"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div @click.outside="showEdit = false"
                 class="bg-white rounded-[30px] p-8 w-full max-w-md shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                        <x-lucide-pencil class="w-5 h-5 text-amber-500" />
                    </span>
                    <h3 class="text-xl font-bold text-gray-800">Editar Tasa</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Instrumento" />
                        <x-text-input x-model="editing.instrument_name" class="mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label value="Tasa Anual (%)" />
                        <div class="relative mt-1">
                            <x-text-input x-model="editing.annual_rate" type="number"
                                          step="0.01" min="0" max="999.99" class="w-full pr-8" required />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showEdit = false" class="btn btn-ghost rounded-xl">Cancelar</button>
                        <button type="button" @click="handleEdit()"
                                class="btn bg-primary text-white rounded-xl border-none px-6"
                                :disabled="loading" :class="{ 'loading': loading }">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
function ratesPage() {
    return {
        showCreate: false,
        showEdit: false,
        loading: false,
        form: { instrument_name: '', annual_rate: '' },
        editing: { id: null, instrument_name: '', annual_rate: '' },

        openEdit(rate) {
            this.editing = { id: rate.id, instrument_name: rate.instrument_name, annual_rate: rate.annual_rate };
            this.showEdit = true;
        },

        async handleCreate() {
            this.loading = true;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                await axios.post('{{ route('tasas.store') }}', this.form, {
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                Toast.success('Tasa creada correctamente.');
                this.showCreate = false;
                this.form = { instrument_name: '', annual_rate: '' };
                window._ratesTable?.replaceData();
            } catch (e) {
                const errors = e.response?.data?.errors;
                Toast.error(errors ? Object.values(errors).flat().join(' ') : 'Error al crear la tasa.');
            } finally {
                this.loading = false;
            }
        },

        async handleEdit() {
            this.loading = true;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                await axios.put(`/tasas/${this.editing.id}`, this.editing, {
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                Toast.success('Tasa actualizada correctamente.');
                this.showEdit = false;
                window._ratesTable?.replaceData();
            } catch (e) {
                const errors = e.response?.data?.errors;
                Toast.error(errors ? Object.values(errors).flat().join(' ') : 'Error al actualizar la tasa.');
            } finally {
                this.loading = false;
            }
        },
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' };

    const IC = {
        pencil: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>`,
        trash: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>`,
    };

    window._ratesTable = new Tabulator('#rates-table', {
        ajaxURL: '{{ route('tasas.index') }}',
        ajaxConfig: { headers },
        layout: 'fitColumns',
        locale: 'es',
        langs: { es: { pagination: { page_size: 'Filas', first: '«', prev: '‹', next: '›', last: '»', all: 'Todo' } } },
        placeholder: '<div class="flex flex-col items-center gap-3 text-gray-400 py-12"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg><p class="text-sm">No hay tasas registradas.</p></div>',
        columns: [
            {
                title: 'Instrumento', field: 'instrument_name', minWidth: 200,
                formatter: (cell) => {
                    return `<div class="flex items-center gap-2"><span class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#E6AD56" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></span><span class="font-medium text-gray-800">${cell.getValue() ?? ''}</span></div>`;
                },
            },
            {
                title: 'Tasa Anual', field: 'annual_rate', width: 130, hozAlign: 'right',
                formatter: (cell) => `<span class="font-mono text-lg font-bold text-primary">${parseFloat(cell.getValue()).toFixed(2)}<span class="text-sm text-gray-400 font-normal">%</span></span>`,
            },
            {
                title: 'Actualizado', field: 'updated_at', width: 160,
                formatter: (cell) => `<span class="text-gray-400 text-xs">${new Date(cell.getValue()).toLocaleString('es-MX', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>`,
            },
            {
                title: 'Acciones', field: 'id', width: 90, headerSort: false,
                formatter: () => `<div class="flex gap-1">
                    <button class="btn btn-ghost btn-xs rounded-lg text-gray-500" data-action="edit" title="Editar">${IC.pencil}</button>
                    <button class="btn btn-ghost btn-xs rounded-lg text-error" data-action="delete" title="Eliminar">${IC.trash}</button>
                </div>`,
            },
        ],
    });

    document.getElementById('rates-table').addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const rowEl = btn.closest('.tabulator-row');
        if (!rowEl) return;
        const row = window._ratesTable.getRowFromElement(rowEl);
        const data = row.getData();

        if (btn.dataset.action === 'edit') {
            window.dispatchEvent(new CustomEvent('open-edit-rate', { detail: data }));
        }

        if (btn.dataset.action === 'delete') {
            const result = await Modal.delete('¿Eliminar tasa?', `Se eliminará "${data.instrument_name}".`);
            if (!result.isConfirmed) return;
            try {
                await axios.delete(`/tasas/${data.id}`, { headers });
                row.delete();
                Toast.success('Tasa eliminada correctamente.');
            } catch { Toast.error('Error al eliminar la tasa.'); }
        }
    });
});
</script>
@endpush
</x-app-layout>
