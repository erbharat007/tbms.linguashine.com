@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-success small font-weight-bold']) }}>
        {{ $status }}
    </div>
@endif
