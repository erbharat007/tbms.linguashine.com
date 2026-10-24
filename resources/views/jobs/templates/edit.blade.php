<x-app-layout>
    <x-slot name="header">
        <x-page-header title="EDIT JOB CHAIN TEMPLATE" subtitle="{{ $jobChainTemplate->name }}">
            <x-slot name="actions">
                <a href="{{ route('job-chain-templates.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to templates
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="font-weight-bold mb-0 text-primary">Edit Template: {{ $jobChainTemplate->name }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('job-chain-templates.update', $jobChainTemplate) }}">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-500">Name <span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $jobChainTemplate->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label font-weight-500">Description</label>
                    <div class="col-sm-6">
                        <input type="text" name="description" class="form-control" value="{{ old('description', $jobChainTemplate->description) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-primary btn-sm mb-3 shadow-sm" id="addChainLinkBtn">
                        <i class="fas fa-plus mr-1"></i> Add job chain link
                    </button>
                </div>

                <div class="p-4 bg-light rounded border mb-4">
                    <div id="chainContainer" class="d-flex align-items-center flex-wrap">
                        @php $steps = $jobChainTemplate->steps ?? []; @endphp
                        @forelse ($steps as $idx => $step)
                            @php $srvId = is_array($step) ? ($step['service_id'] ?? null) : $step; @endphp
                            <div class="chain-box p-3 bg-white border rounded shadow-sm mr-2 mb-2" style="min-width: 200px;">
                                <label class="small font-weight-bold mb-1">Service <span class="text-danger">*</span></label>
                                <select name="services[]" class="form-control form-control-sm mb-2" required>
                                    <option value="">Select Service</option>
                                    @foreach ($services as $srv)
                                        <option value="{{ $srv->id }}" @selected($srvId == $srv->id)>{{ $srv->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger btn-xs btn-block remove-link-btn" onclick="removeBox(this)">
                                    Remove
                                </button>
                            </div>

                            @if ($idx < count($steps) - 1)
                                <div class="chain-arrow mx-2 mb-2">
                                    <i class="fas fa-arrow-right fa-lg text-secondary"></i>
                                </div>
                            @endif
                        @empty
                            <div class="chain-box p-3 bg-white border rounded shadow-sm mr-2 mb-2" style="min-width: 200px;">
                                <label class="small font-weight-bold mb-1">Service <span class="text-danger">*</span></label>
                                <select name="services[]" class="form-control form-control-sm mb-2" required>
                                    <option value="">Select Service</option>
                                    @foreach ($services as $srv)
                                        <option value="{{ $srv->id }}">{{ $srv->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger btn-xs btn-block remove-link-btn" onclick="removeBox(this)">
                                    Remove
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="border-top pt-3 d-flex">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm mr-2">Update</button>
                    <a href="{{ route('job-chain-templates.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const servicesOptions = `@foreach($services as $srv)<option value="{{ $srv->id }}">{{ $srv->name }}</option>@endforeach`;

        document.getElementById('addChainLinkBtn').addEventListener('click', function () {
            const container = document.getElementById('chainContainer');
            const currentBoxes = container.querySelectorAll('.chain-box');

            if (currentBoxes.length > 0) {
                const arrow = document.createElement('div');
                arrow.className = 'chain-arrow mx-2 mb-2';
                arrow.innerHTML = '<i class="fas fa-arrow-right fa-lg text-secondary"></i>';
                container.appendChild(arrow);
            }

            const box = document.createElement('div');
            box.className = 'chain-box p-3 bg-white border rounded shadow-sm mr-2 mb-2';
            box.style.minWidth = '200px';
            box.innerHTML = `
                <label class="small font-weight-bold mb-1">Service <span class="text-danger">*</span></label>
                <select name="services[]" class="form-control form-control-sm mb-2" required>
                    <option value="">Select Service</option>
                    ${servicesOptions}
                </select>
                <button type="button" class="btn btn-outline-danger btn-xs btn-block remove-link-btn" onclick="removeBox(this)">
                    Remove
                </button>
            `;
            container.appendChild(box);
        });

        function removeBox(btn) {
            const box = btn.closest('.chain-box');
            const container = document.getElementById('chainContainer');
            const boxes = container.querySelectorAll('.chain-box');

            if (boxes.length <= 1) {
                alert('A job chain template must have at least one service link.');
                return;
            }

            if (box.previousElementSibling && box.previousElementSibling.classList.contains('chain-arrow')) {
                box.previousElementSibling.remove();
            } else if (box.nextElementSibling && box.nextElementSibling.classList.contains('chain-arrow')) {
                box.nextElementSibling.remove();
            }

            box.remove();
        }
    </script>
    @endpush
</x-app-layout>
