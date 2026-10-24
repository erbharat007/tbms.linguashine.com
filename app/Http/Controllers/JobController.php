<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\JobChecklist;
use App\Models\JobTag;
use App\Models\LanguagePair;
use App\Models\Project;
use App\Models\ProjectJob;
use App\Models\Service;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobController extends Controller
{
    /**
     * Display a listing of jobs with filters, sorting, bulk actions, and export.
     */
    public function index(Request $request): View|StreamedResponse
    {
        $query = ProjectJob::with([
            'project',
            'service',
            'languagePair.sourceLanguage',
            'languagePair.targetLanguage',
            'vendor',
            'unit',
            'currency',
            'primaryManager',
            'tags',
        ]);

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Vendor filter
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Service filter
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Language pair filter
        if ($request->filled('language_pair_id')) {
            $query->where('language_pair_id', $request->language_pair_id);
        }

        // Primary manager filter
        if ($request->filled('primary_manager_id')) {
            $query->where('primary_manager_id', $request->primary_manager_id);
        }

        // Search filter (Code, Name)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['code', 'name', 'status', 'started_at', 'deadline_at', 'completed_at', 'work_amount', 'payables', 'id'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('id');
        }

        // Export to Excel / CSV if requested
        if ($request->get('export') === 'excel') {
            return $this->exportToExcel($query->get());
        }

        $jobs = $query->paginate(25)->withQueryString();

        // Data for filters
        $projects = Project::orderBy('name')->get(['id', 'code', 'name']);
        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        $services = Service::orderBy('name')->get(['id', 'name']);
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->get();
        $managers = User::where('is_manager', true)->orWhere('role_id', 2)->orderBy('name')->get();

        return view('jobs.index', compact(
            'jobs',
            'projects',
            'vendors',
            'services',
            'languagePairs',
            'managers'
        ));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create(Request $request): View
    {
        $selectedProjectId = $request->query('project_id');
        $selectedProject = $selectedProjectId ? Project::find($selectedProjectId) : null;

        $projects = Project::orderBy('name')->get(['id', 'code', 'name', 'currency_id', 'primary_manager_id']);
        $services = Service::orderBy('name')->get(['id', 'name']);
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->get();
        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        $units = Unit::orderBy('name')->get(['id', 'name']);
        $currencies = Currency::orderBy('code')->get(['id', 'code', 'symbol']);
        $managers = User::where('is_manager', true)->orWhere('role_id', 2)->orderBy('name')->get();
        $checklists = JobChecklist::where('is_enabled', true)->orderBy('name')->get();
        $tags = JobTag::orderBy('name')->get();

        return view('jobs.create', compact(
            'projects',
            'selectedProject',
            'services',
            'languagePairs',
            'vendors',
            'units',
            'currencies',
            'managers',
            'checklists',
            'tags'
        ));
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'service_id' => 'required|exists:services,id',
            'language_pair_id' => 'required|exists:language_pairs,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'vendor_contact_id' => 'nullable|exists:vendor_contacts,id',
            'status' => 'required|in:draft,offered,in_progress,completed,cancelled',
            'started_at' => 'nullable|date',
            'deadline_at' => 'nullable|date',
            'unit_id' => 'nullable|exists:units,id',
            'work_amount' => 'nullable|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'checklist_id' => 'nullable|exists:job_checklists,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:job_tags,id',
        ]);

        $workAmount = (float) ($validated['work_amount'] ?? 0);
        $pricePerUnit = (float) ($validated['price_per_unit'] ?? 0);
        $payables = round($workAmount * $pricePerUnit, 2);

        $code = ProjectJob::generateCode();

        $job = new ProjectJob();
        $job->code = $code;
        $job->name = $validated['name'];
        $job->project_id = $validated['project_id'];
        $job->service_id = $validated['service_id'];
        $job->language_pair_id = $validated['language_pair_id'];
        $job->vendor_id = $validated['vendor_id'] ?? null;
        $job->vendor_contact_id = $validated['vendor_contact_id'] ?? null;
        $job->status = $validated['status'];
        $job->started_at = $validated['started_at'] ?? ($validated['status'] === 'in_progress' ? now() : null);
        $job->deadline_at = $validated['deadline_at'] ?? null;
        $job->completed_at = $validated['status'] === 'completed' ? now() : null;
        $job->unit_id = $validated['unit_id'] ?? null;
        $job->work_amount = $workAmount;
        $job->price_per_unit = $pricePerUnit;
        $job->payables = $payables;
        $job->currency_id = $validated['currency_id'] ?? null;
        $job->primary_manager_id = $validated['primary_manager_id'] ?? auth()->id();
        $job->checklist_id = $validated['checklist_id'] ?? null;
        $job->description = $validated['description'] ?? null;
        $job->instructions = $validated['instructions'] ?? null;
        $job->notes = $validated['notes'] ?? null;
        $job->created_by = auth()->id();
        $job->save();

        if (!empty($validated['tags'])) {
            $job->tags()->sync($validated['tags']);
        }

        // Recalculate parent project payables & margins
        $this->syncProjectFinances($job->project_id);

        return redirect()->route('jobs.show', $job)->with('success', "Job {$job->code} created successfully.");
    }

    /**
     * Display the specified job.
     */
    public function show(ProjectJob $job): View
    {
        $job->load([
            'project',
            'service',
            'languagePair.sourceLanguage',
            'languagePair.targetLanguage',
            'vendor',
            'vendorContact',
            'unit',
            'currency',
            'primaryManager',
            'checklist',
            'tags',
            'tender',
            'creator',
        ]);

        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job.
     */
    public function edit(ProjectJob $job): View
    {
        $job->load('tags');

        $projects = Project::orderBy('name')->get(['id', 'code', 'name', 'currency_id', 'primary_manager_id']);
        $services = Service::orderBy('name')->get(['id', 'name']);
        $languagePairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->get();
        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        $units = Unit::orderBy('name')->get(['id', 'name']);
        $currencies = Currency::orderBy('code')->get(['id', 'code', 'symbol']);
        $managers = User::where('is_manager', true)->orWhere('role_id', 2)->orderBy('name')->get();
        $checklists = JobChecklist::where('is_enabled', true)->orderBy('name')->get();
        $tags = JobTag::orderBy('name')->get();

        return view('jobs.edit', compact(
            'job',
            'projects',
            'services',
            'languagePairs',
            'vendors',
            'units',
            'currencies',
            'managers',
            'checklists',
            'tags'
        ));
    }

    /**
     * Update the specified job in storage.
     */
    public function update(Request $request, ProjectJob $job): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'service_id' => 'required|exists:services,id',
            'language_pair_id' => 'required|exists:language_pairs,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'vendor_contact_id' => 'nullable|exists:vendor_contacts,id',
            'status' => 'required|in:draft,offered,in_progress,completed,cancelled',
            'started_at' => 'nullable|date',
            'deadline_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'unit_id' => 'nullable|exists:units,id',
            'work_amount' => 'nullable|numeric|min:0',
            'price_per_unit' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'primary_manager_id' => 'nullable|exists:users,id',
            'checklist_id' => 'nullable|exists:job_checklists,id',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:job_tags,id',
        ]);

        $workAmount = (float) ($validated['work_amount'] ?? 0);
        $pricePerUnit = (float) ($validated['price_per_unit'] ?? 0);
        $payables = round($workAmount * $pricePerUnit, 2);

        $oldProjectId = $job->project_id;

        $job->name = $validated['name'];
        $job->project_id = $validated['project_id'];
        $job->service_id = $validated['service_id'];
        $job->language_pair_id = $validated['language_pair_id'];
        $job->vendor_id = $validated['vendor_id'] ?? null;
        $job->vendor_contact_id = $validated['vendor_contact_id'] ?? null;
        $job->status = $validated['status'];
        $job->started_at = $validated['started_at'] ?? $job->started_at;
        $job->deadline_at = $validated['deadline_at'] ?? null;
        $job->completed_at = $validated['completed_at'] ?? ($validated['status'] === 'completed' && !$job->completed_at ? now() : $job->completed_at);
        $job->unit_id = $validated['unit_id'] ?? null;
        $job->work_amount = $workAmount;
        $job->price_per_unit = $pricePerUnit;
        $job->payables = $payables;
        $job->currency_id = $validated['currency_id'] ?? null;
        $job->primary_manager_id = $validated['primary_manager_id'] ?? $job->primary_manager_id;
        $job->checklist_id = $validated['checklist_id'] ?? null;
        $job->description = $validated['description'] ?? null;
        $job->instructions = $validated['instructions'] ?? null;
        $job->notes = $validated['notes'] ?? null;
        $job->save();

        if (isset($validated['tags'])) {
            $job->tags()->sync($validated['tags']);
        } else {
            $job->tags()->detach();
        }

        // Recalculate parent project payables & margins
        $this->syncProjectFinances($job->project_id);
        if ($oldProjectId !== $job->project_id) {
            $this->syncProjectFinances($oldProjectId);
        }

        return redirect()->route('jobs.show', $job)->with('success', "Job {$job->code} updated successfully.");
    }

    /**
     * Remove the specified job from storage.
     */
    public function destroy(ProjectJob $job): RedirectResponse
    {
        $projectId = $job->project_id;
        $code = $job->code;
        $job->delete();

        $this->syncProjectFinances($projectId);

        return redirect()->route('jobs.index')->with('success', "Job {$code} deleted successfully.");
    }

    /**
     * Transition a single job's status.
     */
    public function updateStatus(Request $request, ProjectJob $job): RedirectResponse
    {
        $status = $request->input('status');
        $validStatuses = ['draft', 'offered', 'in_progress', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status provided.');
        }

        $job->status = $status;
        if ($status === 'in_progress' && !$job->started_at) {
            $job->started_at = now();
        } elseif ($status === 'completed' && !$job->completed_at) {
            $job->completed_at = now();
        }
        $job->save();

        $this->syncProjectFinances($job->project_id);

        return back()->with('success', "Job status changed to {$job->status_label}.");
    }

    /**
     * Perform bulk status transition on selected jobs.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('job_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Please select at least one job.');
        }

        $validActions = [
            'offer' => 'offered',
            'start' => 'in_progress',
            'accept' => 'completed',
            'cancel' => 'cancelled',
            'draft' => 'draft',
        ];

        if (!isset($validActions[$action])) {
            return back()->with('error', 'Invalid bulk action requested.');
        }

        $newStatus = $validActions[$action];
        $affectedProjects = [];

        $jobs = ProjectJob::whereIn('id', $ids)->get();
        foreach ($jobs as $job) {
            $job->status = $newStatus;
            if ($newStatus === 'in_progress' && !$job->started_at) {
                $job->started_at = now();
            } elseif ($newStatus === 'completed' && !$job->completed_at) {
                $job->completed_at = now();
            }
            $job->save();

            $affectedProjects[$job->project_id] = true;
        }

        foreach (array_keys($affectedProjects) as $projectId) {
            $this->syncProjectFinances($projectId);
        }

        $count = count($jobs);
        $actionLabel = ucfirst($action);
        return back()->with('success', "Successfully applied '{$actionLabel}' to {$count} job(s).");
    }

    /**
     * Recalculate and persist total payables for the parent project.
     */
    protected function syncProjectFinances(?int $projectId): void
    {
        if (!$projectId) {
            return;
        }

        $project = Project::find($projectId);
        if (!$project) {
            return;
        }

        $totalPayables = ProjectJob::where('project_id', $projectId)
            ->where('status', '!=', 'cancelled')
            ->sum('payables');

        $project->total_to_pay = $totalPayables;
        $project->recalculateFinances();
        $project->save();
    }

    /**
     * Stream CSV export matching Protemos Jobs table headers.
     */
    protected function exportToExcel($jobs): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="jobs_export_' . date('Y_m_d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($jobs) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel
            fputs($file, "\xEF\xBB\xBF");

            // Header matching Protemos columns
            fputcsv($file, [
                'Code',
                'Name',
                'Service',
                'Language pair',
                'Deadline at',
                'Vendor',
                'Status',
                'Started at',
                'Completed at',
                'Tags',
                'Work amount',
                'Payables, USD',
                'Project code',
                'Primary project manager',
            ]);

            foreach ($jobs as $job) {
                $tags = $job->tags->pluck('name')->implode(', ');
                $langPair = $job->languagePair ? "{$job->languagePair->sourceLanguage?->name} - {$job->languagePair->targetLanguage?->name}" : '—';

                fputcsv($file, [
                    $job->code,
                    $job->name,
                    $job->service?->name ?? '—',
                    $langPair,
                    $job->deadline_at ? $job->deadline_at->format('Y-m-d H:i') : '',
                    $job->vendor?->name ?? '—',
                    $job->status_label,
                    $job->started_at ? $job->started_at->format('Y-m-d H:i') : '',
                    $job->completed_at ? $job->completed_at->format('Y-m-d H:i') : '',
                    $tags,
                    $job->work_amount,
                    number_format($job->payables, 2),
                    $job->project?->code ?? '—',
                    $job->primaryManager?->name ?? '—',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
