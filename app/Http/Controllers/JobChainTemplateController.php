<?php

namespace App\Http\Controllers;

use App\Models\JobChainTemplate;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobChainTemplateController extends Controller
{
    /**
     * Display a listing of job chain templates.
     */
    public function index(): View
    {
        $templates = JobChainTemplate::with('creator')->latest('id')->paginate(25);
        $services = Service::all()->keyBy('id');

        return view('jobs.templates.index', compact('templates', 'services'));
    }

    /**
     * Show form for creating a new job chain template.
     */
    public function create(): View
    {
        $services = Service::orderBy('name')->get();
        return view('jobs.templates.create', compact('services'));
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'services' => 'required|array|min:1',
            'services.*' => 'required|exists:services,id',
        ]);

        $steps = [];
        foreach ($validated['services'] as $serviceId) {
            $steps[] = ['service_id' => (int) $serviceId];
        }

        $template = new JobChainTemplate();
        $template->name = $validated['name'];
        $template->description = $validated['description'] ?? null;
        $template->steps = $steps;
        $template->created_by = auth()->id();
        $template->save();

        return redirect()->route('job-chain-templates.index')->with('success', "Job chain template '{$template->name}' created successfully.");
    }

    /**
     * Show the form for editing the template.
     */
    public function edit(JobChainTemplate $jobChainTemplate): View
    {
        $services = Service::orderBy('name')->get();
        return view('jobs.templates.edit', compact('jobChainTemplate', 'services'));
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, JobChainTemplate $jobChainTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'services' => 'required|array|min:1',
            'services.*' => 'required|exists:services,id',
        ]);

        $steps = [];
        foreach ($validated['services'] as $serviceId) {
            $steps[] = ['service_id' => (int) $serviceId];
        }

        $jobChainTemplate->name = $validated['name'];
        $jobChainTemplate->description = $validated['description'] ?? null;
        $jobChainTemplate->steps = $steps;
        $jobChainTemplate->save();

        return redirect()->route('job-chain-templates.index')->with('success', "Job chain template '{$jobChainTemplate->name}' updated successfully.");
    }

    /**
     * Remove the specified template.
     */
    public function destroy(JobChainTemplate $jobChainTemplate): RedirectResponse
    {
        $name = $jobChainTemplate->name;
        $jobChainTemplate->delete();

        return redirect()->route('job-chain-templates.index')->with('success', "Template '{$name}' deleted.");
    }
}
