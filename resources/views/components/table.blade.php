@props(['heads' => []])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        @if (count($heads))
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($heads as $head)
                        @php($align = str_contains(strtolower($head), 'action') ? 'text-right' : 'text-left')
                        <th class="whitespace-nowrap px-4 py-3 {{ $align }} text-xs font-semibold uppercase tracking-wider text-gray-500">
                            {{ $head }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100 bg-white">
            {{ $slot }}
        </tbody>
    </table>
</div>
