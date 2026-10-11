@props(['title' => null, 'subtitle' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @isset($title)
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h3 class="card-title mb-0">{{ $title }}</h3>
                @isset($subtitle)
                    <small class="text-muted d-block mt-1">{{ $subtitle }}</small>
                @endisset
            </div>
            @isset($actions)
                <div class="card-tools d-flex align-items-center" style="float:none;">{{ $actions }}</div>
            @endisset
        </div>
    @endisset

    <div class="{{ $noPadding ? 'card-body p-0' : 'card-body' }}">
        {{ $slot }}
    </div>
</div>
