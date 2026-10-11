@props(['name', 'value' => '', 'options' => [], 'placeholder' => 'Search...', 'disabled' => false, 'id' => null])

{{--
    A searchable dropdown backed by Select2 (jQuery), themed for Bootstrap 4 / AdminLTE.
    Renders a native <select> so it posts like a normal form field and works without Alpine.

    Usage:
    <x-searchable-select name="country_id" :value="old('country_id', $model->country_id)"
                         :options="$countries" placeholder="Search country" />

    $options should be an assoc array [value => label] (or a flat list).
--}}

@php
    $selectId = $id ?? ($name . '_' . \Illuminate\Support\Str::random(5));
    $selected = (string) ($value ?? '');
@endphp

<select id="{{ $selectId }}"
        name="{{ $name }}"
        @disabled($disabled)
        {{ $attributes->merge(['class' => 'form-control select2-search', 'data-placeholder' => $placeholder]) }}
        data-allow-clear="true">
    <option value=""></option>
    @foreach ($options as $optValue => $optLabel)
        <option value="{{ $optValue }}" @selected($selected === (string) $optValue)>{{ $optLabel }}</option>
    @endforeach
</select>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $(function () {
                $('.select2-search').each(function () {
                    $(this).select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: $(this).data('placeholder') || 'Select...',
                        allowClear: $(this).data('allow-clear') === true,
                    });
                });
            });
        </script>
    @endpush
@endonce
