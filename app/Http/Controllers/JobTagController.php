<?php

namespace App\Http\Controllers;

use App\Models\JobTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobTagController extends Controller
{
    /**
     * Display a listing of job tags matching Protemos columns.
     */
    public function index(): View
    {
        $tags = JobTag::withCount('jobs')->orderBy('name')->paginate(25);

        return view('jobs.tags.index', compact('tags'));
    }

    /**
     * Store a newly created job tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:job_tags,name',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);

        JobTag::create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?: '#3c8dbc',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('job-tags.index')->with('success', "Tag '{$validated['name']}' created successfully.");
    }

    /**
     * Update the specified job tag.
     */
    public function update(Request $request, JobTag $jobTag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:job_tags,name,' . $jobTag->id,
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:255',
        ]);

        $jobTag->update([
            'name' => $validated['name'],
            'color' => $validated['color'] ?: '#3c8dbc',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('job-tags.index')->with('success', "Tag '{$jobTag->name}' updated successfully.");
    }

    /**
     * Remove the specified job tag.
     */
    public function destroy(JobTag $jobTag): RedirectResponse
    {
        $name = $jobTag->name;
        $jobTag->jobs()->detach();
        $jobTag->delete();

        return redirect()->route('job-tags.index')->with('success', "Tag '{$name}' deleted.");
    }
}
