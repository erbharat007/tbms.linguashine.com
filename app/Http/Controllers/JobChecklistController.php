<?php

namespace App\Http\Controllers;

use App\Models\JobChecklist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobChecklistController extends Controller
{
    /**
     * Display a listing of job checklists matching Protemos columns.
     */
    public function index(): View
    {
        $checklists = JobChecklist::with(['updater', 'creator'])->latest('updated_at')->paginate(25);

        return view('jobs.checklists.index', compact('checklists'));
    }

    /**
     * Show form for creating a new checklist.
     */
    public function create(): View
    {
        return view('jobs.checklists.create');
    }

    /**
     * Store a newly created checklist.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_enabled' => 'nullable|boolean',
            'note_for_vendor' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*' => 'nullable|string|max:500',
        ]);

        $cleanItems = [];
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                if (trim($item) !== '') {
                    $cleanItems[] = ['text' => trim($item), 'is_required' => true];
                }
            }
        }

        $checklist = new JobChecklist();
        $checklist->name = $validated['name'];
        $checklist->is_enabled = $request->has('is_enabled');
        $checklist->note_for_vendor = $validated['note_for_vendor'] ?? null;
        $checklist->items = $cleanItems;
        $checklist->created_by = auth()->id();
        $checklist->updated_by = auth()->id();
        $checklist->save();

        return redirect()->route('job-checklists.index')->with('success', "Checklist '{$checklist->name}' created successfully.");
    }

    /**
     * Show form for editing the checklist.
     */
    public function edit(JobChecklist $jobChecklist): View
    {
        return view('jobs.checklists.edit', compact('jobChecklist'));
    }

    /**
     * Update the checklist.
     */
    public function update(Request $request, JobChecklist $jobChecklist): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_enabled' => 'nullable|boolean',
            'note_for_vendor' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*' => 'nullable|string|max:500',
        ]);

        $cleanItems = [];
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $item) {
                if (trim($item) !== '') {
                    $cleanItems[] = ['text' => trim($item), 'is_required' => true];
                }
            }
        }

        $jobChecklist->name = $validated['name'];
        $jobChecklist->is_enabled = $request->has('is_enabled');
        $jobChecklist->note_for_vendor = $validated['note_for_vendor'] ?? null;
        $jobChecklist->items = $cleanItems;
        $jobChecklist->updated_by = auth()->id();
        $jobChecklist->save();

        return redirect()->route('job-checklists.index')->with('success', "Checklist '{$jobChecklist->name}' updated successfully.");
    }

    /**
     * Remove the checklist.
     */
    public function destroy(JobChecklist $jobChecklist): RedirectResponse
    {
        $name = $jobChecklist->name;
        $jobChecklist->delete();

        return redirect()->route('job-checklists.index')->with('success', "Checklist '{$name}' deleted.");
    }
}
