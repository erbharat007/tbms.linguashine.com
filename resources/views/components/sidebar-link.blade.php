@props(['active' => false, 'disabled' => false, 'icon' => null, 'child' => false])

@php
    $base = $child
        ? 'group flex items-center gap-2 rounded-md py-1.5 pl-11 pr-3 text-[13px] transition'
        : 'group flex items-center gap-3 rounded-md px-3 py-2 font-medium transition';

    if ($disabled) {
        $classes = $base . ' cursor-not-allowed text-gray-500';
    } elseif ($active) {
        $classes = $base . ' bg-indigo-600 text-white';
    } else {
        $classes = $base . ' text-gray-300 hover:bg-gray-800 hover:text-white';
    }
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if ($disabled) aria-disabled="true" @endif>
    @isset($icon)
        <span class="shrink-0">{{ $icon }}</span>
    @endisset
    <span class="flex-1">{{ $slot }}</span>
    @if ($disabled)
        <span class="rounded bg-gray-700/70 px-1.5 py-0.5 text-[9px] font-medium uppercase tracking-wide text-gray-400">Soon</span>
    @endif
</a>
