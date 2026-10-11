@props(['title', 'subtitle' => null])

<div class="row mb-2 align-items-center">
    <div class="col-sm">
        <h1 class="m-0 h3">{{ $title }}</h1>
        @isset($subtitle)
            <small class="text-muted">{{ $subtitle }}</small>
        @endisset
    </div>
    @isset($actions)
        <div class="col-sm-auto mt-2 mt-sm-0 text-sm-right">
            {{ $actions }}
        </div>
    @endisset
</div>
