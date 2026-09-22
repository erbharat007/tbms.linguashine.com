@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-6 flex items-start justify-between rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800">&times;</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
         class="mb-6 flex items-start justify-between rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="ml-4 text-red-600 hover:text-red-800">&times;</button>
    </div>
@endif
