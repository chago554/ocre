@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-xl text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mb-6 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-xl text-sm font-medium">
        {{ session('error') }}
    </div>
@endif
