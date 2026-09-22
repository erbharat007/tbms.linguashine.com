<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function index(): View
    {
        $languages = Language::orderBy('name')->paginate(20);

        return view('reference.languages.index', compact('languages'));
    }

    public function create(): View
    {
        return view('reference.languages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Language::create($this->validated($request));

        return redirect()->route('languages.index')
            ->with('success', 'Language created successfully.');
    }

    public function show(Language $language): RedirectResponse
    {
        return redirect()->route('languages.edit', $language);
    }

    public function edit(Language $language): View
    {
        return view('reference.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language): RedirectResponse
    {
        $language->update($this->validated($request, $language));

        return redirect()->route('languages.index')
            ->with('success', 'Language updated successfully.');
    }

    public function destroy(Language $language): RedirectResponse
    {
        $language->delete();

        return redirect()->route('languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    /**
     * Validate the request for storing/updating a language.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Language $language = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', Rule::unique('languages', 'code')->ignore($language)],
        ]);
    }
}
