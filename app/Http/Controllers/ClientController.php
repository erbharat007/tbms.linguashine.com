<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\ClientSource;
use App\Models\ClientStatus;
use App\Models\Country;
use App\Models\LegalEntity;
use App\Models\Timezone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $clients = Client::query()
            ->with(['status', 'country', 'primaryContact'])
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('clients.index', [
            'clients' => $clients,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View
    {
        return view('clients.create', $this->formData() + [
            'client' => new Client(['type' => 'company']),
            'contact' => null,
        ]);
    }

    /**
     * Store a newly created client and its primary contact.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $client = DB::transaction(function () use ($data) {
            $client = Client::create($this->clientAttributes($data));

            $client->contacts()->create($this->contactAttributes($data) + [
                'is_primary' => true,
            ]);

            return $client;
        });

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the client detail page (General info tab).
     */
    public function show(Client $client): View
    {
        $client->load(['status', 'source', 'legalEntity', 'currency', 'country', 'primaryContact.timezone']);

        return view('clients.show', [
            'client' => $client,
            'contact' => $client->primaryContact,
        ]);
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client): View
    {
        $client->load('primaryContact');

        return view('clients.edit', $this->formData() + [
            'client' => $client,
            'contact' => $client->primaryContact,
        ]);
    }

    /**
     * Update the specified client and its primary contact.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($client, $data) {
            $client->update($this->clientAttributes($data));

            $contact = $client->primaryContact()->first();

            if ($contact) {
                $contact->update($this->contactAttributes($data));
            } else {
                $client->contacts()->create($this->contactAttributes($data) + [
                    'is_primary' => true,
                ]);
            }
        });

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Shared data for the create/edit forms (dropdown options).
     *
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'legalEntities' => LegalEntity::orderBy('name')->pluck('name', 'id'),
            'statuses' => ClientStatus::orderBy('sort_order')->pluck('name', 'id'),
            'sources' => ClientSource::orderBy('sort_order')->pluck('name', 'id'),
            'countries' => Country::orderBy('name')->pluck('name', 'id'),
            'timezones' => Timezone::orderBy('label')->pluck('label', 'id'),
        ];
    }

    /**
     * Extract the client's own attributes from validated data.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function clientAttributes(array $data): array
    {
        return [
            'name' => $data['name'],
            'type' => $data['type'],
            'legal_entity_id' => $data['legal_entity_id'] ?? null,
            'client_status_id' => $data['client_status_id'] ?? null,
            'client_source_id' => $data['client_source_id'] ?? null,
            'country_id' => $data['country_id'] ?? null,
            'region' => $data['region'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            'tax_number' => $data['tax_number'] ?? null,
            'vat_number' => $data['vat_number'] ?? null,
            'website' => $data['website'] ?? null,
            'note' => $data['note'] ?? null,
        ];
    }

    /**
     * Extract the primary contact's attributes from validated data.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function contactAttributes(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'timezone_id' => $data['timezone_id'] ?? null,
            'gender' => $data['gender'] ?? null,
            'phone' => $data['phone'] ?? null,
            'skype' => $data['skype'] ?? null,
            'can_login' => (bool) ($data['can_login'] ?? false),
        ];
    }
}
