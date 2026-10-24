<?php

namespace App\Http\Controllers;

use App\Models\JobTender;
use App\Models\ProjectJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobTenderController extends Controller
{
    /**
     * Display a listing of job tenders matching Protemos columns.
     */
    public function index(Request $request): View
    {
        $query = JobTender::with([
            'job.project',
            'job.service',
            'job.languagePair.sourceLanguage',
            'job.languagePair.targetLanguage',
            'job.unit',
            'job.currency',
            'creator',
        ]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('code', 'like', "%{$search}%")
                  ->orWhereHas('job', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                  });
        }

        $tenders = $query->latest('id')->paginate(25)->withQueryString();

        return view('jobs.tenders.index', compact('tenders'));
    }

    /**
     * Show form for creating a new tender.
     */
    public function create(Request $request): View
    {
        $selectedJobId = $request->query('job_id');
        $eligibleJobs = ProjectJob::whereDoesntHave('tender')
            ->whereIn('status', ['draft', 'offered'])
            ->with(['project', 'service', 'languagePair.sourceLanguage', 'languagePair.targetLanguage', 'unit', 'currency'])
            ->get();

        return view('jobs.tenders.create', compact('eligibleJobs', 'selectedJobId'));
    }

    /**
     * Store a newly created tender.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_job_id' => 'required|exists:project_jobs,id|unique:job_tenders,project_job_id',
            'type' => 'required|in:first_claimer,manager_selection_fixed,manager_selection_negotiable',
            'deadline_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $tender = new JobTender();
        $tender->code = JobTender::generateCode();
        $tender->project_job_id = $validated['project_job_id'];
        $tender->type = $validated['type'];
        $tender->status = 'active';
        $tender->started_at = now();
        $tender->deadline_at = $validated['deadline_at'] ?? null;
        $tender->participating_vendors_count = 0;
        $tender->notes = $validated['notes'] ?? null;
        $tender->created_by = auth()->id();
        $tender->save();

        // Update linked job to offered if currently draft
        $job = ProjectJob::find($validated['project_job_id']);
        if ($job && $job->status === 'draft') {
            $job->status = 'offered';
            $job->save();
        }

        return redirect()->route('job-tenders.index')->with('success', "Tender {$tender->code} launched successfully.");
    }

    /**
     * Display the specified tender.
     */
    public function show(JobTender $jobTender): View
    {
        $jobTender->load([
            'job.project',
            'job.service',
            'job.languagePair.sourceLanguage',
            'job.languagePair.targetLanguage',
            'job.unit',
            'job.currency',
            'creator',
        ]);

        return view('jobs.tenders.show', compact('jobTender'));
    }

    /**
     * Update the tender.
     */
    public function update(Request $request, JobTender $jobTender): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,active,completed,cancelled',
            'deadline_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $jobTender->status = $validated['status'];
        if ($validated['status'] === 'completed' || $validated['status'] === 'cancelled') {
            $jobTender->ended_at = now();
        }
        $jobTender->deadline_at = $validated['deadline_at'] ?? $jobTender->deadline_at;
        $jobTender->notes = $validated['notes'] ?? $jobTender->notes;
        $jobTender->save();

        return back()->with('success', "Tender {$jobTender->code} updated successfully.");
    }

    /**
     * Remove the tender.
     */
    public function destroy(JobTender $jobTender): RedirectResponse
    {
        $code = $jobTender->code;
        $jobTender->delete();

        return redirect()->route('job-tenders.index')->with('success', "Tender {$code} deleted.");
    }
}
