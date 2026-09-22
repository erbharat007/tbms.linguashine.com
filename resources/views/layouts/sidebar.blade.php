@php($user = auth()->user())
@use('App\Support\Navigation')

<aside
    class="fixed inset-y-0 left-0 z-30 w-56 transform overflow-y-auto bg-gray-900 text-gray-300 transition-transform duration-200 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <!-- Brand -->
    <div class="flex h-16 items-center border-b border-gray-800 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" class="h-9 w-auto" />
        </a>
    </div>

    <!-- Navigation -->
    <nav class="space-y-1 px-3 py-5 text-sm">
        @foreach (config('navigation') as $item)
            @continue(! Navigation::itemVisible($user, $item))

            @if (empty($item['children']))
                {{-- Top-level single link --}}
                <x-sidebar-link
                    :href="$item['route'] ? route($item['route']) : '#'"
                    :active="Navigation::isActive($item)"
                    :disabled="empty($item['route'])"
                >
                    <x-slot name="icon"><x-sidebar-icon :name="$item['icon'] ?? 'dashboard'" /></x-slot>
                    {{ $item['label'] }}
                </x-sidebar-link>
            @else
                {{-- Group with children --}}
                <x-sidebar-group :title="$item['label']" :icon="$item['icon'] ?? null" :open="Navigation::groupActive($item)">
                    @foreach ($item['children'] as $child)
                        @continue(! Navigation::canSee($user, $child))
                        <x-sidebar-link
                            :href="$child['route'] ? route($child['route']) : '#'"
                            :active="Navigation::isActive($child)"
                            :disabled="empty($child['route'])"
                            child
                        >
                            {{ $child['label'] }}
                        </x-sidebar-link>
                    @endforeach
                </x-sidebar-group>
            @endif
        @endforeach
    </nav>
</aside>
