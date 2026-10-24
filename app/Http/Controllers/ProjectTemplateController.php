<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\LanguagePair;
use App\Models\ProjectTemplate;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectTemplateController extends Controller
{
    /**
     * Display a listing of project templates.
     */
    public function index(): View
    {
        $templates = ProjectTemplate::with([
            'client',
            'specialization',
            'primaryManager',
            'languagePairs.sourceLanguage',
            'languagePairs.targetLanguage',
        ])->orderBy('id', 'desc')->paginate(15);

        return view('projects.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new project template.
     */
    public function create(): View
    {
        $clients = Client::with('contacts')->orderBy('name')->get();
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->where('is_enabled', true)->get();
        $specializations = Specialization::all();
        $managers = User::where('is_manager', true)->orderBy('name')->get();

        return view('projects.templates.create', compact(
            'clients',
            'languagePairs',
            'specializations',
            'managers'
        ));
    }

    /**
     * Store a newly created project template.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'client_contact_id' => ['nullable', 'exists:client_contacts,id'],
            'language_pair_ids' => ['nullable', 'array'],
            'language_pair_ids.*' => ['exists:language_pairs,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'description' => ['nullable', 'string'],
            'internal_note' => ['nullable', 'string'],
            'primary_manager_id' => ['nullable', 'exists:users,id'],
            'secondary_manager_id' => ['nullable', 'exists:users,id', 'different:primary_manager_id'],
        ]);

        $template = ProjectTemplate::create([
            'name' => $validated['name'],
            'client_id' => $validated['client_id'] ?? null,
            'client_contact_id' => $validated['client_contact_id'] ?? null,
            'specialization_id' => $validated['specialization_id'] ?? null,
            'primary_manager_id' => $validated['primary_manager_id'] ?? null,
            'secondary_manager_id' => $validated['secondary_manager_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'internal_note' => $validated['internal_note'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if (!empty($validated['language_pair_ids'])) {
            $template->languagePairs()->sync($validated['language_pair_ids']);
        }

        return redirect()->route('project-templates.index')
            ->with('status', "Project template '{$template->name}' created successfully.");
    }

    /**
     * Show the form for editing the template.
     */
    public function edit(ProjectTemplate $projectTemplate): View
    {
        $projectTemplate->load('languagePairs');
        $clients = Client::with('contacts')->orderBy('name')->get();
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->where('is_enabled', true)->get();
        $specializations = Specialization::all();
        $managers = User::where('is_manager', true)->orderBy('name')->get();

        return view('projects.templates.edit', compact(
            'projectTemplate',
            'clients',
            'languagePairs',
            'specializations',
            'managers'
        ));
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, ProjectTemplate $projectTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'client_contact_id' => ['nullable', 'exists:client_contacts,id'],
            'language_pair_ids' => ['nullable', 'array'],
            'language_pair_ids.*' => ['exists:language_pairs,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'description' => ['nullable', 'string'],
            'internal_note' => ['nullable', 'string'],
            'primary_manager_id' => ['nullable', 'exists:users,id'],
            'secondary_manager_id' => ['nullable', 'exists:users,id', 'different:primary_manager_id'],
        ]);

        $projectTemplate->update([
            'name' => $validated['name'],
            'client_id' => $validated['client_id'] ?? null,
            'client_contact_id' => $validated['client_contact_id'] ?? null,
            'specialization_id' => $validated['specialization_id'] ?? null,
            'primary_manager_id' => $validated['primary_manager_id'] ?? null,
            'secondary_manager_id' => $validated['secondary_manager_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'internal_note' => $validated['internal_note'] ?? null,
        ]);

        $projectTemplate->languagePairs()->sync($validated['language_pair_ids'] ?? []);

        return redirect()->route('project-templates.index')
            ->with('status', "Project template '{$projectTemplate->name}' updated successfully.");
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(ProjectTemplate $projectTemplate): RedirectResponse
    {
        $name = $projectTemplate->name;
        $projectTemplate->delete();

        return redirect()->route('project-templates.index')
            ->with('status', "Project template '{$name}' deleted.");
    }
}
