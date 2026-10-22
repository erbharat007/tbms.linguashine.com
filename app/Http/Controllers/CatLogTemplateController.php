<?php

namespace App\Http\Controllers;

use App\Models\CatLogTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatLogTemplateController extends Controller
{
    /**
     * Display a listing of CAT log conversion schemes.
     */
    public function index(): View
    {
        $schemes = CatLogTemplate::with('creator')->latest()->paginate(20);

        return view('settings.cat-log-templates.index', compact('schemes'));
    }

    /**
     * Show the form for creating a new CAT log conversion scheme.
     */
    public function create(): View
    {
        $scheme = new CatLogTemplate([
            'rate_repetitions' => 100.00,
            'rate_100' => 100.00,
            'rate_95_99' => 100.00,
            'rate_85_94' => 100.00,
            'rate_75_84' => 100.00,
            'rate_50_74' => 100.00,
            'rate_no_match' => 100.00,
        ]);

        return view('settings.cat-log-templates.create', compact('scheme'));
    }

    /**
     * Store a newly created scheme in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateScheme($request);
        $validated['created_by'] = auth()->id();

        $this->handleDefaultUnsets($validated);

        CatLogTemplate::create($validated);

        return redirect()->route('cat-log-templates.index')
            ->with('status', 'CAT log conversion scheme created successfully.');
    }

    /**
     * Show the form for editing the specified scheme.
     */
    public function edit(CatLogTemplate $catLogTemplate): View
    {
        return view('settings.cat-log-templates.edit', ['scheme' => $catLogTemplate]);
    }

    /**
     * Update the specified scheme in storage.
     */
    public function update(Request $request, CatLogTemplate $catLogTemplate): RedirectResponse
    {
        $validated = $this->validateScheme($request);

        $this->handleDefaultUnsets($validated, $catLogTemplate->id);

        $catLogTemplate->update($validated);

        return redirect()->route('cat-log-templates.index')
            ->with('status', 'CAT log conversion scheme updated successfully.');
    }

    /**
     * Remove the specified scheme from storage.
     */
    public function destroy(CatLogTemplate $catLogTemplate): RedirectResponse
    {
        $catLogTemplate->delete();

        return redirect()->route('cat-log-templates.index')
            ->with('status', 'CAT log conversion scheme deleted successfully.');
    }

    /**
     * Validate incoming scheme request.
     */
    protected function validateScheme(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_default_client_account' => 'nullable|boolean',
            'is_default_client_profile' => 'nullable|boolean',
            'is_default_vendor_account' => 'nullable|boolean',
            'is_default_vendor_profile' => 'nullable|boolean',
            'rate_repetitions' => 'required|numeric|min:0|max:1000',
            'rate_100' => 'required|numeric|min:0|max:1000',
            'rate_95_99' => 'required|numeric|min:0|max:1000',
            'rate_85_94' => 'required|numeric|min:0|max:1000',
            'rate_75_84' => 'required|numeric|min:0|max:1000',
            'rate_50_74' => 'required|numeric|min:0|max:1000',
            'rate_no_match' => 'required|numeric|min:0|max:1000',
        ]);

        $validated['is_default_client_account'] = $request->boolean('is_default_client_account');
        $validated['is_default_client_profile'] = $request->boolean('is_default_client_profile');
        $validated['is_default_vendor_account'] = $request->boolean('is_default_vendor_account');
        $validated['is_default_vendor_profile'] = $request->boolean('is_default_vendor_profile');

        return $validated;
    }

    /**
     * If setting as default, clear previous default.
     */
    protected function handleDefaultUnsets(array $validated, ?int $ignoreId = null): void
    {
        foreach (['is_default_client_account', 'is_default_client_profile', 'is_default_vendor_account', 'is_default_vendor_profile'] as $flag) {
            if (!empty($validated[$flag])) {
                CatLogTemplate::query()
                    ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                    ->update([$flag => false]);
            }
        }
    }
}
