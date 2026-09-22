<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdfTemplateRequest;
use App\Models\PdfTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PdfTemplateController extends Controller
{
    /**
     * List all PDF templates (all object types).
     */
    public function index(): View
    {
        return view('settings.pdf-templates.index', [
            'templates' => PdfTemplate::orderBy('object_type')->orderBy('name')->paginate(20),
        ]);
    }

    /**
     * Show the create form. Supports cloning via ?clone=<id>.
     */
    public function create(Request $request): View
    {
        $template = null;

        if ($request->filled('clone')) {
            $source = PdfTemplate::findOrFail($request->integer('clone'));
            $template = $source->replicate();
            $template->name = $source->name.' (copy)';
        }

        return view('settings.pdf-templates.create', [
            'template' => $template,
        ]);
    }

    /**
     * Store a new template.
     */
    public function store(PdfTemplateRequest $request): RedirectResponse
    {
        PdfTemplate::create($request->validated());

        return redirect()->route('pdf-templates.index')
            ->with('success', 'PDF template created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(PdfTemplate $pdfTemplate): View
    {
        return view('settings.pdf-templates.edit', [
            'template' => $pdfTemplate,
        ]);
    }

    /**
     * Update a template.
     */
    public function update(PdfTemplateRequest $request, PdfTemplate $pdfTemplate): RedirectResponse
    {
        $pdfTemplate->update($request->validated());

        return redirect()->route('pdf-templates.index')
            ->with('success', 'PDF template updated.');
    }

    /**
     * Delete a template.
     */
    public function destroy(PdfTemplate $pdfTemplate): RedirectResponse
    {
        $pdfTemplate->delete();

        return redirect()->route('pdf-templates.index')
            ->with('success', 'PDF template deleted.');
    }
}
