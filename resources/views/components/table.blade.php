@props(['heads' => []])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover table-striped mb-0']) }}>
        @if (count($heads))
            <thead>
                <tr>
                    @foreach ($heads as $head)
                        @php($align = str_contains(strtolower($head), 'action') ? 'text-right' : '')
                        <th class="{{ $align }}">{{ $head }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
