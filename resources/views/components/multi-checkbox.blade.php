@props(['name', 'options' => [], 'selected' => [], 'placeholder' => 'Search...'])

{{--
    A searchable multi-select rendered as a scrollable checkbox list (jQuery).
    Posts an array of selected ids as {name}[].
    $options: [id => label]. $selected: array of pre-checked ids.
--}}
@php($mcId = $name . '_mc')

<div class="border rounded multi-checkbox" id="{{ $mcId }}">
    <div class="border-bottom p-2">
        <input type="text" class="form-control form-control-sm mc-search" placeholder="{{ $placeholder }}">
    </div>
    <div class="p-2 mc-list" style="max-height: 14rem; overflow-y: auto;">
        @forelse ($options as $id => $label)
            <div class="custom-control custom-checkbox mc-item py-1" data-label="{{ \Illuminate\Support\Str::lower($label) }}">
                <input type="checkbox" class="custom-control-input" id="{{ $mcId }}_{{ $id }}" name="{{ $name }}[]" value="{{ $id }}"
                       @checked(in_array($id, old($name, $selected)))>
                <label class="custom-control-label" for="{{ $mcId }}_{{ $id }}">{{ $label }}</label>
            </div>
        @empty
            <p class="text-muted small px-2 py-1 mb-0">No options available.</p>
        @endforelse
    </div>
</div>

@once
    @push('scripts')
    <script>
        $(function () {
            $('.multi-checkbox').each(function () {
                var $box = $(this);
                $box.find('.mc-search').on('input', function () {
                    var q = $(this).val().toLowerCase();
                    $box.find('.mc-item').each(function () {
                        $(this).toggle($(this).data('label').indexOf(q) !== -1);
                    });
                });
            });
        });
    </script>
    @endpush
@endonce
