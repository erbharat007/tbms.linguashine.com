@php
    $user = auth()->user();

    // Map our navigation icon keys to Font Awesome classes.
    $iconMap = [
        'dashboard' => 'fas fa-tachometer-alt',
        'access-control' => 'fas fa-user-shield',
        'settings' => 'fas fa-cog',
        'system-values' => 'fas fa-sliders-h',
        'email-templates' => 'fas fa-envelope',
        'clients' => 'fas fa-user-tie',
        'vendors' => 'fas fa-truck',
        'projects' => 'fas fa-project-diagram',
        'jobs' => 'fas fa-tasks',
        'todos' => 'fas fa-check-circle',
        'expenses' => 'fas fa-receipt',
        'files' => 'fas fa-folder',
        'quotes' => 'fas fa-file-invoice-dollar',
        'reports' => 'fas fa-chart-bar',
    ];
    $faIcon = fn ($key) => $iconMap[$key] ?? 'far fa-circle';
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center justify-content-center">
        <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" class="brand-image-custom">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach (config('navigation') as $item)
                    @continue(! \App\Support\Navigation::itemVisible($user, $item))

                    @if (empty($item['children']))
                        {{-- Single top-level link --}}
                        @php($disabled = empty($item['route']))
                        <li class="nav-item">
                            <a href="{{ $disabled ? '#' : route($item['route']) }}"
                               class="nav-link {{ \App\Support\Navigation::isActive($item) ? 'active' : '' }} {{ $disabled ? 'disabled-link' : '' }}">
                                <i class="nav-icon {{ $faIcon($item['icon'] ?? 'dashboard') }}"></i>
                                <p>
                                    {{ $item['label'] }}
                                    @if ($disabled)<span class="badge badge-secondary right">Soon</span>@endif
                                </p>
                            </a>
                        </li>
                    @else
                        {{-- Group with children (treeview) --}}
                        @php($groupActive = \App\Support\Navigation::groupActive($item))
                        <li class="nav-item {{ $groupActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $groupActive ? 'active' : '' }}">
                                <i class="nav-icon {{ $faIcon($item['icon'] ?? 'dashboard') }}"></i>
                                <p>
                                    {{ $item['label'] }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($item['children'] as $child)
                                    @continue(! \App\Support\Navigation::canSee($user, $child))

                                    @if (! empty($child['children']))
                                        @php($subActive = \App\Support\Navigation::groupActive($child))
                                        <li class="nav-item {{ $subActive ? 'menu-open' : '' }}">
                                            <a href="{{ !empty($child['route']) ? route($child['route']) : '#' }}"
                                               class="nav-link {{ $subActive ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    {{ $child['label'] }}
                                                    <i class="right fas fa-angle-left"></i>
                                                </p>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                @foreach ($child['children'] as $grandchild)
                                                    @continue(! \App\Support\Navigation::canSee($user, $grandchild))
                                                    @php($grandchildDisabled = empty($grandchild['route']))
                                                    <li class="nav-item">
                                                        <a href="{{ $grandchildDisabled ? '#' : route($grandchild['route']) }}"
                                                           class="nav-link {{ \App\Support\Navigation::isActive($grandchild) ? 'active' : '' }} {{ $grandchildDisabled ? 'disabled-link' : '' }}"
                                                           style="padding-left: 2.2rem;">
                                                            <i class="far fa-dot-circle nav-icon" style="font-size: 0.7rem;"></i>
                                                            <p>
                                                                {{ $grandchild['label'] }}
                                                                @if ($grandchildDisabled)<span class="badge badge-secondary right">Soon</span>@endif
                                                            </p>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        @php($childDisabled = empty($child['route']))
                                        <li class="nav-item">
                                            <a href="{{ $childDisabled ? '#' : route($child['route']) }}"
                                               class="nav-link {{ \App\Support\Navigation::isActive($child) ? 'active' : '' }} {{ $childDisabled ? 'disabled-link' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    {{ $child['label'] }}
                                                    @if ($childDisabled)<span class="badge badge-secondary right">Soon</span>@endif
                                                </p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
