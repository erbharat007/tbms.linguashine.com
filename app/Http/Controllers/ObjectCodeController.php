<?php

namespace App\Http\Controllers;

use App\Models\CodeSetting;
use App\Models\ObjectCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ObjectCodeController extends Controller
{
    /**
     * Main Object codes view page.
     */
    public function index(): View
    {
        return view('settings.object-codes.index', [
            'objectCodes' => ObjectCode::orderBy('sort_order')->get(),
            'codeSetting' => CodeSetting::current(),
        ]);
    }

    /**
     * Form to edit code prefixes (all 11 objects).
     */
    public function editPrefixes(): View
    {
        return view('settings.object-codes.prefixes', [
            'objectCodes' => ObjectCode::orderBy('sort_order')->get(),
        ]);
    }

    public function updatePrefixes(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'prefixes' => ['required', 'array'],
            'prefixes.*' => ['nullable', 'string', 'max:10'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['prefixes'] as $id => $prefix) {
                ObjectCode::whereKey($id)->update(['prefix' => $prefix]);
            }
        });

        return redirect()->route('object-codes.index')
            ->with('success', 'Code prefixes updated.');
    }

    /**
     * Form to edit next IDs (only objects with a standalone sequence).
     */
    public function editNextIds(): View
    {
        return view('settings.object-codes.next-ids', [
            'objectCodes' => ObjectCode::orderBy('sort_order')->get()->filter->hasSequence(),
        ]);
    }

    public function updateNextIds(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'next_ids' => ['required', 'array'],
            'next_ids.*' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['next_ids'] as $id => $nextId) {
                ObjectCode::whereKey($id)->update(['next_id' => $nextId]);
            }
        });

        return redirect()->route('object-codes.index')
            ->with('success', 'Next IDs updated.');
    }

    /**
     * Form to edit other code settings (year in invoice codes).
     */
    public function editSettings(): View
    {
        return view('settings.object-codes.settings', [
            'codeSetting' => CodeSetting::current(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        CodeSetting::current()->update([
            'include_year_in_invoice_codes' => $request->boolean('include_year_in_invoice_codes'),
        ]);

        return redirect()->route('object-codes.index')
            ->with('success', 'Code settings updated.');
    }
}
