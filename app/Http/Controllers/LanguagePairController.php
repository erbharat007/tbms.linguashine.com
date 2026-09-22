<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\LanguagePair;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguagePairController extends Controller
{
    /**
     * List all language pairs.
     */
    public function index(): View
    {
        $pairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])
            ->join('languages as src', 'src.id', '=', 'language_pairs.source_language_id')
            ->join('languages as tgt', 'tgt.id', '=', 'language_pairs.target_language_id')
            ->orderBy('src.name')
            ->orderBy('tgt.name')
            ->select('language_pairs.*')
            ->paginate(30);

        return view('settings.language-pairs.index', compact('pairs'));
    }

    /**
     * Show the "Add language pairs" form (multi-select source + target).
     */
    public function create(): View
    {
        return view('settings.language-pairs.create', [
            'languages' => Language::orderBy('name')->pluck('name', 'id'),
        ]);
    }

    /**
     * Store a language pair for every combination of the selected source
     * and target languages.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'source_language_ids' => ['required', 'array', 'min:1'],
            'source_language_ids.*' => ['integer', 'exists:languages,id'],
            'target_language_ids' => ['required', 'array', 'min:1'],
            'target_language_ids.*' => ['integer', 'exists:languages,id'],
        ]);

        $created = 0;
        $skipped = 0;

        foreach ($data['source_language_ids'] as $sourceId) {
            foreach ($data['target_language_ids'] as $targetId) {
                if ((int) $sourceId === (int) $targetId) {
                    $skipped++;
                    continue; // skip same-language pairs
                }

                $pair = LanguagePair::firstOrCreate(
                    ['source_language_id' => $sourceId, 'target_language_id' => $targetId],
                    ['is_enabled' => true],
                );

                $pair->wasRecentlyCreated ? $created++ : $skipped++;
            }
        }

        $message = "{$created} language pair(s) added.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped (already existed or same language).";
        }

        return redirect()->route('language-pairs.index')->with('success', $message);
    }

    /**
     * Toggle a pair's enabled state.
     */
    public function toggle(LanguagePair $languagePair): RedirectResponse
    {
        $languagePair->update(['is_enabled' => ! $languagePair->is_enabled]);

        return redirect()->route('language-pairs.index')
            ->with('success', 'Language pair ' . ($languagePair->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    /**
     * Remove a language pair.
     */
    public function destroy(LanguagePair $languagePair): RedirectResponse
    {
        $languagePair->delete();

        return redirect()->route('language-pairs.index')
            ->with('success', 'Language pair removed.');
    }
}
