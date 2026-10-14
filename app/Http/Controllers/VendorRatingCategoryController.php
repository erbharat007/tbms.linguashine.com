<?php

namespace App\Http\Controllers;

use App\Models\VendorRatingCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorRatingCategoryController extends Controller
{
    /**
     * Display a listing of vendor rating categories (Vendors > Rating categories).
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim();

        $categories = VendorRatingCategory::query()
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('vendors.rating-categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    /**
     * Show create category form.
     */
    public function create(): View
    {
        return view('vendors.rating-categories.create', [
            'category' => new VendorRatingCategory(['weight' => 25, 'status' => 'active']),
        ]);
    }

    /**
     * Store new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        VendorRatingCategory::create($data);

        return redirect()->route('vendor-rating-categories.index')
            ->with('success', 'Rating category created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(VendorRatingCategory $vendorRatingCategory): View
    {
        return view('vendors.rating-categories.edit', [
            'category' => $vendorRatingCategory,
        ]);
    }

    /**
     * Update category.
     */
    public function update(Request $request, VendorRatingCategory $vendorRatingCategory): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $vendorRatingCategory->update($data);

        return redirect()->route('vendor-rating-categories.index')
            ->with('success', 'Rating category updated successfully.');
    }

    /**
     * Delete category.
     */
    public function destroy(VendorRatingCategory $vendorRatingCategory): RedirectResponse
    {
        $vendorRatingCategory->delete();

        return redirect()->route('vendor-rating-categories.index')
            ->with('success', 'Rating category deleted successfully.');
    }
}
