@props(['color' => 'gray'])

@php
    // Map our color names to Bootstrap/AdminLTE badge classes.
    $map = [
        'gray' => 'badge-secondary',
        'green' => 'badge-success',
        'red' => 'badge-danger',
        'blue' => 'badge-info',
        'indigo' => 'badge-primary',
        'yellow' => 'badge-warning',
    ];
    $class = $map[$color] ?? 'badge-secondary';
@endphp

<span {{ $attributes->merge(['class' => "badge {$class}"]) }}>{{ $slot }}</span>
