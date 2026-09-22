@props(['title', 'open' => false, 'icon' => null])

<div x-data="{ open: @js($open) }">
    <button type="button" @click="open = ! open"
            class="group flex w-full items-center gap-3 rounded-md px-3 py-2 font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
        @isset($icon)
            <x-sidebar-icon :name="$icon" />
        @endisset
        <span class="flex-1 text-left">{{ $title }}</span>
        <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
             fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="mt-1 space-y-1 pb-1"
         x-cloak>
        {{ $slot }}
    </div>
</div>
