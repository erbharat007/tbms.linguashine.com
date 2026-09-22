<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientInvoiceEmailTemplateRequest;
use App\Models\ClientInvoiceEmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientInvoiceEmailTemplateController extends Controller
{
    /**
     * List all client invoice email templates.
     */
    public function index(): View
    {
        return view('settings.client-invoice-email-templates.index', [
            'templates' => ClientInvoiceEmailTemplate::orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the create form. Supports cloning via ?clone=<id>.
     */
    public function create(Request $request): View
    {
        $template = null;

        if ($request->filled('clone')) {
            $source = ClientInvoiceEmailTemplate::findOrFail($request->integer('clone'));
            $template = new ClientInvoiceEmailTemplate([
                'name' => $source->name.' (copy)',
                'subject' => $source->subject,
                'body' => $source->body,
            ]);
        }

        return view('settings.client-invoice-email-templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(ClientInvoiceEmailTemplateRequest $request): RedirectResponse
    {
        ClientInvoiceEmailTemplate::create($request->validated());

        return redirect()->route('client-invoice-email-templates.index')
            ->with('success', 'Client invoice email template created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(ClientInvoiceEmailTemplate $clientInvoiceEmailTemplate): View
    {
        return view('settings.client-invoice-email-templates.edit', [
            'template' => $clientInvoiceEmailTemplate,
        ]);
    }

    /**
     * Update a template.
     */
    public function update(ClientInvoiceEmailTemplateRequest $request, ClientInvoiceEmailTemplate $clientInvoiceEmailTemplate): RedirectResponse
    {
        $clientInvoiceEmailTemplate->update($request->validated());

        return redirect()->route('client-invoice-email-templates.index')
            ->with('success', 'Client invoice email template updated.');
    }

    /**
     * Delete a template.
     */
    public function destroy(ClientInvoiceEmailTemplate $clientInvoiceEmailTemplate): RedirectResponse
    {
        $clientInvoiceEmailTemplate->delete();

        return redirect()->route('client-invoice-email-templates.index')
            ->with('success', 'Client invoice email template deleted.');
    }
}
