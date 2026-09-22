<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteEmailTemplateRequest;
use App\Models\QuoteEmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteEmailTemplateController extends Controller
{
    /**
     * List all quote email templates.
     */
    public function index(): View
    {
        return view('settings.quote-email-templates.index', [
            'templates' => QuoteEmailTemplate::orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the create form. Supports cloning via ?clone=<id>.
     */
    public function create(Request $request): View
    {
        $template = null;

        if ($request->filled('clone')) {
            $source = QuoteEmailTemplate::findOrFail($request->integer('clone'));
            $template = new QuoteEmailTemplate([
                'name' => $source->name.' (copy)',
                'subject' => $source->subject,
                'body' => $source->body,
            ]);
        }

        return view('settings.quote-email-templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(QuoteEmailTemplateRequest $request): RedirectResponse
    {
        QuoteEmailTemplate::create($request->validated());

        return redirect()->route('quote-email-templates.index')
            ->with('success', 'Quote email template created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(QuoteEmailTemplate $quoteEmailTemplate): View
    {
        return view('settings.quote-email-templates.edit', [
            'template' => $quoteEmailTemplate,
        ]);
    }

    /**
     * Update a template.
     */
    public function update(QuoteEmailTemplateRequest $request, QuoteEmailTemplate $quoteEmailTemplate): RedirectResponse
    {
        $quoteEmailTemplate->update($request->validated());

        return redirect()->route('quote-email-templates.index')
            ->with('success', 'Quote email template updated.');
    }

    /**
     * Delete a template.
     */
    public function destroy(QuoteEmailTemplate $quoteEmailTemplate): RedirectResponse
    {
        $quoteEmailTemplate->delete();

        return redirect()->route('quote-email-templates.index')
            ->with('success', 'Quote email template deleted.');
    }
}
