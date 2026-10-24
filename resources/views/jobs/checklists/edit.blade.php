<x-app-layout>
    <x-slot name="header">
        <x-page-header title="EDIT JOB CHECKLIST" subtitle="{{ $jobChecklist->name }}">
            <x-slot name="actions">
                <a href="{{ route('job-checklists.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to checklists
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Edit Checklist: {{ $jobChecklist->name }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('job-checklists.update', $jobChecklist) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-500">Name <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $jobChecklist->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_enabled" value="1" class="custom-control-input" id="is_enabled" @checked(old('is_enabled', $jobChecklist->is_enabled))>
                                    <label class="custom-control-label font-weight-500" for="is_enabled">Is enabled</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-500">Note for vendor</label>
                            <div class="col-sm-9">
                                <textarea name="note_for_vendor" rows="3" class="form-control">{{ old('note_for_vendor', $jobChecklist->note_for_vendor) }}</textarea>
                            </div>
                        </div>

                        {{-- Checklist Items --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-500">Checklist Items</label>
                            <div class="col-sm-9">
                                <div id="itemsContainer">
                                    @php $items = $jobChecklist->items ?? []; @endphp
                                    @forelse ($items as $item)
                                        <div class="input-group mb-2 item-row">
                                            <input type="text" name="items[]" class="form-control" value="{{ is_array($item) ? ($item['text'] ?? '') : $item }}">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeItemRow(this)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="input-group mb-2 item-row">
                                            <input type="text" name="items[]" class="form-control" placeholder="Check item description">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger" onclick="removeItemRow(this)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="addItemBtn">
                                    <i class="fas fa-plus mr-1"></i> Add item
                                </button>
                            </div>
                        </div>

                        <div class="border-top pt-3 d-flex">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm mr-2">Update</button>
                            <a href="{{ route('job-checklists.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('addItemBtn').addEventListener('click', function () {
            const container = document.getElementById('itemsContainer');
            const row = document.createElement('div');
            row.className = 'input-group mb-2 item-row';
            row.innerHTML = `
                <input type="text" name="items[]" class="form-control" placeholder="Check item description">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-danger" onclick="removeItemRow(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
        });

        function removeItemRow(btn) {
            const container = document.getElementById('itemsContainer');
            if (container.querySelectorAll('.item-row').length > 1) {
                btn.closest('.item-row').remove();
            } else {
                btn.closest('.item-row').querySelector('input').value = '';
            }
        }
    </script>
    @endpush
</x-app-layout>
