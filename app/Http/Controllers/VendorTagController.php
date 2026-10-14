<?php

namespace App\Http\Controllers;

use App\Models\VendorTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorTagController extends Controller
{
    /**
     * Display a listing of vendor tags (Vendors > Tags).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $tags = VendorTag::query()
            ->withCount('vendors')
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('vendors.tags.index', [
            'tags' => $tags,
            'search' => $search,
        ]);
    }

    /**
     * Show create tag form.
     */
    public function create(): View
    {
        return view('vendors.tags.create', [
            'tag' => new VendorTag(['color' => 'blue']),
        ]);
    }

    /**
     * Store new tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:vendor_tags,name'],
            'color' => ['required', 'string', 'in:blue,green,red,yellow,info,secondary'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        VendorTag::create($data);

        return redirect()->route('vendor-tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorTag $vendorTag): View
    {
        return view('vendors.tags.edit', [
            'tag' => $vendorTag,
        ]);
    }

    /**
     * Update tag.
     */
    public function update(Request $request, VendorTag $vendorTag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:vendor_tags,name,' . $vendorTag->id],
            'color' => ['required', 'string', 'in:blue,green,red,yellow,info,secondary'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $vendorTag->update($data);

        return redirect()->route('vendor-tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Delete tag.
     */
    public function destroy(VendorTag $vendorTag): RedirectResponse
    {
        $vendorTag->delete();

        return redirect()->route('vendor-tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
