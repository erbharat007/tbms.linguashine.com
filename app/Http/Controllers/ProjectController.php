<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Currency;
use App\Models\LanguagePair;
use App\Models\LegalEntity;
use App\Models\Project;
use App\Models\ProjectTag;
use App\Models\ProjectTemplate;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with status filters, search, and KPI summaries.
     */
    public function index(Request $request): View
    {
        $query = Project::with([
            'client',
            'primaryManager',
            'currency',
            'tags',
            'languagePairs.sourceLanguage',
            'languagePairs.targetLanguage',
        ]);

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Client filter
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Manager filter
        if ($request->filled('manager_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('primary_manager_id', $request->manager_id)
                  ->orWhere('secondary_manager_id', $request->manager_id);
            });
        }

        // Search by code, name, or description
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', $search)
                  ->orWhere('name', 'like', $search)
                  ->orWhere('description', 'like', $search)
                  ->orWhereHas('client', fn ($cq) => $cq->where('name', 'like', $search));
            });
        }

        // Sorting
        $sort = $request->input('sort', 'id');
        $direction = strtolower($request->input('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSorts = [
            'id', 'code', 'name', 'start_at', 'deadline_at', 'status',
            'total_to_receive', 'total_to_pay', 'profit', 'profit_margin'
        ];
        if (in_array($sort, $allowedSorts, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $projects = $query->paginate(15)->withQueryString();

        // KPI metrics
        $allCount = Project::count();
        $inProgressCount = Project::where('status', 'in_progress')->count();
        $completedCount = Project::where('status', 'completed')->count();
        $totalToReceiveSum = Project::sum('total_to_receive');
        $totalToPaySum = Project::sum('total_to_pay');
        $totalProfitSum = Project::sum('profit');

        $clients = Client::orderBy('name')->get();
        $managers = User::where('is_manager', true)->orderBy('name')->get();

        return view('projects.index', compact(
            'projects',
            'allCount',
            'inProgressCount',
            'completedCount',
            'totalToReceiveSum',
            'totalToPaySum',
            'totalProfitSum',
            'clients',
            'managers'
        ));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(Request $request): View
    {
        $clients = Client::with('contacts')->orderBy('name')->get();
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->where('is_enabled', true)->get();
        $specializations = Specialization::all();
        $managers = User::where('is_manager', true)->orderBy('name')->get();
        $legalEntities = LegalEntity::all();
        $tags = ProjectTag::orderBy('name')->get();

        // Pre-fill from template if requested
        $template = null;
        if ($request->filled('template_id')) {
            $template = ProjectTemplate::with('languagePairs')->find($request->template_id);
        }

        return view('projects.create', compact(
            'clients',
            'languagePairs',
            'specializations',
            'managers',
            'legalEntities',
            'tags',
            'template'
        ));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'exists:clients,id'],
            'client_contact_id' => ['nullable', 'exists:client_contacts,id'],
            'language_pair_ids' => ['nullable', 'array'],
            'language_pair_ids.*' => ['exists:language_pairs,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'start_at' => ['required', 'date'],
            'deadline_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'description' => ['nullable', 'string'],
            'internal_note' => ['nullable', 'string'],
            'primary_manager_id' => ['required', 'exists:users,id'],
            'secondary_manager_id' => ['nullable', 'exists:users,id', 'different:primary_manager_id'],
            'legal_entity_id' => ['nullable', 'exists:legal_entities,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:project_tags,id'],
            'total_to_receive' => ['nullable', 'numeric', 'min:0'],
            'total_to_pay' => ['nullable', 'numeric', 'min:0'],
        ]);

        $client = Client::find($validated['client_id']);

        $project = new Project();
        $project->code = Project::generateCode();
        $project->name = $validated['name'];
        $project->client_id = $validated['client_id'];
        $project->client_contact_id = $validated['client_contact_id'] ?? null;
        $project->legal_entity_id = $validated['legal_entity_id'] ?? $client?->legal_entity_id ?? LegalEntity::first()?->id;
        $project->specialization_id = $validated['specialization_id'] ?? null;
        $project->primary_manager_id = $validated['primary_manager_id'];
        $project->secondary_manager_id = $validated['secondary_manager_id'] ?? null;
        $project->start_at = $validated['start_at'];
        $project->deadline_at = $validated['deadline_at'] ?? null;
        $project->status = 'in_progress';
        $project->description = $validated['description'] ?? null;
        $project->internal_note = $validated['internal_note'] ?? null;
        $project->total_to_receive = (float) ($validated['total_to_receive'] ?? 0);
        $project->total_to_pay = (float) ($validated['total_to_pay'] ?? 0);
        $project->currency_id = $client?->currency_id ?? Currency::first()?->id;
        $project->created_by = auth()->id();
        $project->recalculateFinances();
        $project->save();

        // Attach language pairs
        if (!empty($validated['language_pair_ids'])) {
            $project->languagePairs()->sync($validated['language_pair_ids']);
        }

        // Attach tags
        if (!empty($validated['tag_ids'])) {
            $project->tags()->sync($validated['tag_ids']);
        }

        return redirect()->route('projects.show', $project)
            ->with('status', "Project {$project->code} successfully created.");
    }

    /**
     * Display the specified project detail view with tabbed layout.
     */
    public function show(Project $project): View
    {
        $project->load([
            'client.country',
            'clientContact',
            'legalEntity',
            'specialization',
            'primaryManager',
            'secondaryManager',
            'createdBy',
            'currency',
            'languagePairs.sourceLanguage',
            'languagePairs.targetLanguage',
            'tags',
            'jobs.service',
            'jobs.languagePair.sourceLanguage',
            'jobs.languagePair.targetLanguage',
            'jobs.vendor',
            'jobs.unit',
        ]);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the project.
     */
    public function edit(Project $project): View
    {
        $project->load(['languagePairs', 'tags']);
        $clients = Client::with('contacts')->orderBy('name')->get();
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->where('is_enabled', true)->get();
        $specializations = Specialization::all();
        $managers = User::where('is_manager', true)->orderBy('name')->get();
        $legalEntities = LegalEntity::all();
        $tags = ProjectTag::orderBy('name')->get();

        return view('projects.edit', compact(
            'project',
            'clients',
            'languagePairs',
            'specializations',
            'managers',
            'legalEntities',
            'tags'
        ));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'exists:clients,id'],
            'client_contact_id' => ['nullable', 'exists:client_contacts,id'],
            'language_pair_ids' => ['nullable', 'array'],
            'language_pair_ids.*' => ['exists:language_pairs,id'],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'start_at' => ['required', 'date'],
            'deadline_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'status' => ['required', 'in:draft,in_progress,completed,cancelled'],
            'description' => ['nullable', 'string'],
            'internal_note' => ['nullable', 'string'],
            'primary_manager_id' => ['required', 'exists:users,id'],
            'secondary_manager_id' => ['nullable', 'exists:users,id', 'different:primary_manager_id'],
            'legal_entity_id' => ['nullable', 'exists:legal_entities,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:project_tags,id'],
            'total_to_receive' => ['nullable', 'numeric', 'min:0'],
            'total_to_pay' => ['nullable', 'numeric', 'min:0'],
        ]);

        $project->name = $validated['name'];
        $project->client_id = $validated['client_id'];
        $project->client_contact_id = $validated['client_contact_id'] ?? null;
        $project->legal_entity_id = $validated['legal_entity_id'] ?? $project->legal_entity_id;
        $project->specialization_id = $validated['specialization_id'] ?? null;
        $project->primary_manager_id = $validated['primary_manager_id'];
        $project->secondary_manager_id = $validated['secondary_manager_id'] ?? null;
        $project->start_at = $validated['start_at'];
        $project->deadline_at = $validated['deadline_at'] ?? null;
        $project->status = $validated['status'];
        $project->description = $validated['description'] ?? null;
        $project->internal_note = $validated['internal_note'] ?? null;
        if (isset($validated['total_to_receive'])) {
            $project->total_to_receive = (float) $validated['total_to_receive'];
        }
        if (isset($validated['total_to_pay'])) {
            $project->total_to_pay = (float) $validated['total_to_pay'];
        }
        $project->recalculateFinances();
        $project->save();

        // Sync relationships
        $project->languagePairs()->sync($validated['language_pair_ids'] ?? []);
        $project->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()->route('projects.show', $project)
            ->with('status', "Project {$project->code} updated successfully.");
    }

    /**
     * Mark the project as completed.
     */
    public function complete(Project $project): RedirectResponse
    {
        $project->status = 'completed';
        $project->completed_at = now();
        $project->save();

        return redirect()->route('projects.show', $project)
            ->with('status', "Project {$project->code} marked as completed.");
    }

    /**
     * Cancel the project.
     */
    public function cancel(Project $project): RedirectResponse
    {
        $project->status = 'cancelled';
        $project->cancelled_at = now();
        $project->save();

        return redirect()->route('projects.show', $project)
            ->with('status', "Project {$project->code} marked as cancelled.");
    }

    /**
     * Clone an existing project into a new draft project.
     */
    public function clone(Project $project): RedirectResponse
    {
        $newProject = $project->replicate([
            'code', 'status', 'completed_at', 'cancelled_at', 'created_at', 'updated_at'
        ]);
        $newProject->code = Project::generateCode();
        $newProject->name = $project->name . ' (Copy)';
        $newProject->status = 'draft';
        $newProject->start_at = now();
        $newProject->deadline_at = now()->addDays(7);
        $newProject->created_by = auth()->id();
        $newProject->save();

        // Copy language pairs & tags
        $newProject->languagePairs()->sync($project->languagePairs->pluck('id'));
        $newProject->tags()->sync($project->tags->pluck('id'));

        return redirect()->route('projects.show', $newProject)
            ->with('status', "Project {$project->code} cloned as {$newProject->code}.");
    }

    /**
     * Save the project as a reusable Project Template.
     */
    public function saveAsTemplate(Project $project): RedirectResponse
    {
        $template = ProjectTemplate::create([
            'name' => $project->name . ' Template',
            'client_id' => $project->client_id,
            'client_contact_id' => $project->client_contact_id,
            'specialization_id' => $project->specialization_id,
            'primary_manager_id' => $project->primary_manager_id,
            'secondary_manager_id' => $project->secondary_manager_id,
            'description' => $project->description,
            'internal_note' => $project->internal_note,
            'created_by' => auth()->id(),
        ]);

        $template->languagePairs()->sync($project->languagePairs->pluck('id'));

        return redirect()->route('project-templates.index')
            ->with('status', "Template '{$template->name}' created from project {$project->code}.");
    }

    /**
     * Soft delete a project.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $code = $project->code;
        $project->delete();

        return redirect()->route('projects.index')
            ->with('status', "Project {$code} deleted.");
    }

    /**
     * Export projects list to CSV / Excel format.
     */
    public function exportExcel(Request $request): Response
    {
        $projects = Project::with(['client', 'primaryManager', 'currency', 'tags'])->orderBy('id', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="projects_' . date('Y-m-d') . '.csv"',
        ];

        $output = fopen('php://temp', 'r+');
        // UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Code',
            'Name',
            'Client',
            'Start at',
            'Deadline at',
            'Status',
            'Primary manager',
            'Tags',
            'Total to receive',
            'Total to pay',
            'Profit',
            'Profit margin %',
        ]);

        foreach ($projects as $p) {
            fputcsv($output, [
                $p->code,
                $p->name,
                $p->client?->name ?? '—',
                $p->start_at ? $p->start_at->format('Y-m-d H:i') : '',
                $p->deadline_at ? $p->deadline_at->format('Y-m-d H:i') : '',
                $p->status_label,
                $p->primaryManager?->name ?? '—',
                $p->tags->pluck('name')->implode(', '),
                number_format($p->total_to_receive, 2, '.', ''),
                number_format($p->total_to_pay, 2, '.', ''),
                number_format($p->profit, 2, '.', ''),
                number_format($p->profit_margin, 1, '.', '') . ' %',
            ]);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return response($content, 200, $headers);
    }

    /**
     * AJAX helper to return contacts for a client.
     */
    public function clientContactsJson(Client $client)
    {
        return response()->json($client->contacts()->select(['id', 'first_name', 'last_name', 'email'])->get());
    }
}
