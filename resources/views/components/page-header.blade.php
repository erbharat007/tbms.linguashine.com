@props(['title', 'subtitle' => null])

<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $title }}</h2>
        @isset($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endisset
    </div>
    @isset($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
