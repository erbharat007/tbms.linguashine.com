<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultiQuoteEmailTemplateRequest;
use App\Models\MultiQuoteEmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MultiQuoteEmailTemplateController extends Controller
{
    /**
     * List all multi-quote email templates.
     */
    public function index(): View
    {
        return view('settings.multi-quote-email-templates.index', [
            'templates' => MultiQuoteEmailTemplate::orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the create form. Supports cloning via ?clone=<id>.
     */
    public function create(Request $request): View
    {
        $template = null;

        if ($request->filled('clone')) {
            $source = MultiQuoteEmailTemplate::findOrFail($request->integer('clone'));
            $template = new MultiQuoteEmailTemplate([
                'name' => $source->name.' (copy)',
                'subject' => $source->subject,
                'body' => $source->body,
            ]);
        }

        return view('settings.multi-quote-email-templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(MultiQuoteEmailTemplateRequest $request): RedirectResponse
    {
        MultiQuoteEmailTemplate::create($request->validated());

        return redirect()->route('multi-quote-email-templates.index')
            ->with('success', 'Multi-quote email template created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(MultiQuoteEmailTemplate $multiQuoteEmailTemplate): View
    {
        return view('settings.multi-quote-email-templates.edit', [
            'template' => $multiQuoteEmailTemplate,
        ]);
    }

    /**
     * Update a template.
     */
    public function update(MultiQuoteEmailTemplateRequest $request, MultiQuoteEmailTemplate $multiQuoteEmailTemplate): RedirectResponse
    {
        $multiQuoteEmailTemplate->update($request->validated());

        return redirect()->route('multi-quote-email-templates.index')
            ->with('success', 'Multi-quote email template updated.');
    }

    /**
     * Delete a template.
     */
    public function destroy(MultiQuoteEmailTemplate $multiQuoteEmailTemplate): RedirectResponse
    {
        $multiQuoteEmailTemplate->delete();

        return redirect()->route('multi-quote-email-templates.index')
            ->with('success', 'Multi-quote email template deleted.');
    }
}
