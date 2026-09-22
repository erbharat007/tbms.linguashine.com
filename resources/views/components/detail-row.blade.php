@props(['label'])

<div class="flex flex-col gap-1 border-b border-gray-100 px-4 py-3 last:border-0 sm:flex-row sm:gap-4">
    <div class="w-full text-sm font-medium text-gray-500 sm:w-64 sm:shrink-0">{{ $label }}</div>
    <div class="text-sm text-gray-900">{{ $slot }}</div>
</div>
