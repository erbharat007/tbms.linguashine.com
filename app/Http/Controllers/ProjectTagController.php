<?php

namespace App\Http\Controllers;

use App\Models\ProjectTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectTagController extends Controller
{
    /**
     * Display a listing of project tags with project counts.
     */
    public function index(): View
    {
        $tags = ProjectTag::withCount('projects')->orderBy('name')->paginate(20);

        return view('projects.tags.index', compact('tags'));
    }

    /**
     * Store a newly created project tag in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:project_tags,name'],
        ]);

        ProjectTag::create(['name' => trim($validated['name'])]);

        return redirect()->route('project-tags.index')
            ->with('status', "Tag '{$validated['name']}' created successfully.");
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, ProjectTag $projectTag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:project_tags,name,' . $projectTag->id],
        ]);

        $projectTag->update(['name' => trim($validated['name'])]);

        return redirect()->route('project-tags.index')
            ->with('status', "Tag updated to '{$projectTag->name}'.");
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(ProjectTag $projectTag): RedirectResponse
    {
        $name = $projectTag->name;
        $projectTag->delete();

        return redirect()->route('project-tags.index')
            ->with('status', "Tag '{$name}' deleted.");
    }
}
