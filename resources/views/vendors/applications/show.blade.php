<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Application ' . $application->application_number" :subtitle="'Submitted by ' . $application->name">
            <div class="d-flex align-items-center">
                <a href="{{ route('vendor-applications.index') }}" class="btn btn-outline-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Back to list
                </a>

                @if ($application->status === 'pending')
                    <form method="POST" action="{{ route('vendor-applications.approve', $application) }}" class="d-inline mr-2" onsubmit="return confirm('Approve this application and create vendor profile?');">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check mr-1"></i> Approve & Convert to Vendor
                        </button>
                    </form>
                    <form method="POST" action="{{ route('vendor-applications.reject', $application) }}" class="d-inline mr-2" onsubmit="return confirm('Reject this application?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    </form>
                @elseif ($application->vendor)
                    <a href="{{ route('vendors.show', $application->vendor) }}" class="btn btn-outline-primary btn-sm mr-2">
                        <i class="fas fa-external-link-alt mr-1"></i> View Vendor Profile
                    </a>
                @endif
            </div>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Applicant Information">
                <x-detail-row label="Full Name">{{ $application->name }}</x-detail-row>
                <x-detail-row label="Email">
                    <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                </x-detail-row>
                <x-detail-row label="Phone">{{ $application->phone ?: '—' }}</x-detail-row>
                <x-detail-row label="Type">
                    <span class="badge badge-light border text-capitalize">{{ $application->type }}</span>
                </x-detail-row>
                <x-detail-row label="Country">{{ $application->country?->name ?? '—' }}</x-detail-row>
                <x-detail-row label="Native Language">{{ $application->native_language ?: '—' }}</x-detail-row>
            </x-card>

            <x-card title="Competencies & Details" class="mt-4">
                <x-detail-row label="Language Pairs">{{ $application->language_pairs_offered ?: '—' }}</x-detail-row>
                <x-detail-row label="Services">{{ $application->services_offered ?: '—' }}</x-detail-row>
                @if ($application->cv_path)
                    <x-detail-row label="CV / Resume">
                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="btn btn-outline-info btn-xs">
                            <i class="fas fa-file-pdf mr-1"></i> View CV
                        </a>
                    </x-detail-row>
                @endif
                <x-detail-row label="Applicant Notes">{{ $application->note ?: '—' }}</x-detail-row>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Review Status">
                <x-detail-row label="Status">
                    @if ($application->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif ($application->status === 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                    @else
                        <span class="badge badge-warning">Pending Review</span>
                    @endif
                </x-detail-row>
                <x-detail-row label="Submitted">{{ $application->created_at->format('M d, Y H:i') }}</x-detail-row>
                @if ($application->reviewed_at)
                    <x-detail-row label="Reviewed At">{{ $application->reviewed_at->format('M d, Y H:i') }}</x-detail-row>
                    <x-detail-row label="Reviewed By">{{ $application->reviewer?->name ?? 'System' }}</x-detail-row>
                @endif
                @if ($application->vendor)
                    <x-detail-row label="Created Vendor">
                        <a href="{{ route('vendors.show', $application->vendor) }}">{{ $application->vendor->name }}</a>
                    </x-detail-row>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
