<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientContactRequest;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientContactController extends Controller
{
    /**
     * Contact persons tab for a client.
     */
    public function index(Client $client): View
    {
        return view('clients.contacts.index', [
            'client' => $client,
            'contacts' => $client->contacts()->orderByDesc('is_primary')->orderBy('first_name')->get(),
        ]);
    }

    /**
     * Show the create form.
     */
    public function create(Client $client): View
    {
        return view('clients.contacts.create', [
            'client' => $client,
            'contact' => null,
        ]);
    }

    /**
     * Store a new contact person.
     */
    public function store(ClientContactRequest $request, Client $client): RedirectResponse
    {
        $client->contacts()->create($request->validated());

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', 'Contact person added.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Client $client, ClientContact $contact): View
    {
        abort_unless($contact->client_id === $client->id, 404);

        return view('clients.contacts.edit', [
            'client' => $client,
            'contact' => $contact,
        ]);
    }

    /**
     * Update a contact person.
     */
    public function update(ClientContactRequest $request, Client $client, ClientContact $contact): RedirectResponse
    {
        abort_unless($contact->client_id === $client->id, 404);

        $contact->update($request->validated());

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', 'Contact person updated.');
    }

    /**
     * Delete a contact person. The primary contact cannot be deleted.
     */
    public function destroy(Client $client, ClientContact $contact): RedirectResponse
    {
        abort_unless($contact->client_id === $client->id, 404);

        if ($contact->is_primary) {
            return redirect()->route('clients.contacts.index', $client)
                ->with('error', 'The primary contact cannot be deleted.');
        }

        $contact->delete();

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', 'Contact person deleted.');
    }
}
