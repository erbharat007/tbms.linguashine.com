<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLegalEntityRequest;
use App\Http\Requests\UpdateLegalEntityRequest;
use App\Models\Country;
use App\Models\LegalEntity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LegalEntityController extends Controller
{
    public function index(): View
    {
        $legalEntities = LegalEntity::with('country')->orderBy('name')->paginate(20);

        return view('settings.legal-entities.index', compact('legalEntities'));
    }

    public function create(): View
    {
        return view('settings.legal-entities.create', [
            'legalEntity' => new LegalEntity([
                'first_client_invoice_number' => 1,
                'first_vendor_invoice_number' => 1,
            ]),
            'countries' => $this->countries(),
        ]);
    }

    public function store(StoreLegalEntityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data = $this->handleLogo($request, $data);

        LegalEntity::create($data);

        return redirect()->route('legal-entities.index')
            ->with('success', 'Legal entity created successfully.');
    }

    public function show(LegalEntity $legalEntity): RedirectResponse
    {
        return redirect()->route('legal-entities.edit', $legalEntity);
    }

    public function edit(LegalEntity $legalEntity): View
    {
        return view('settings.legal-entities.edit', [
            'legalEntity' => $legalEntity,
            'countries' => $this->countries(),
        ]);
    }

    public function update(UpdateLegalEntityRequest $request, LegalEntity $legalEntity): RedirectResponse
    {
        $data = $request->validated();
        $data = $this->handleLogo($request, $data, $legalEntity);

        $legalEntity->update($data);

        return redirect()->route('legal-entities.index')
            ->with('success', 'Legal entity updated successfully.');
    }

    public function destroy(LegalEntity $legalEntity): RedirectResponse
    {
        if ($legalEntity->logo_path) {
            Storage::disk('public')->delete($legalEntity->logo_path);
        }

        $legalEntity->delete();

        return redirect()->route('legal-entities.index')
            ->with('success', 'Legal entity deleted successfully.');
    }

    /**
     * Store an uploaded logo (if any) and merge its path into the data.
     * Removes the previous logo on replacement.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function handleLogo(Request $request, array $data, ?LegalEntity $entity = null): array
    {
        // The file input is not a fillable column; drop it either way.
        unset($data['logo']);

        if ($request->hasFile('logo')) {
            if ($entity?->logo_path) {
                Storage::disk('public')->delete($entity->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('legal-entities', 'public');
        }

        return $data;
    }

    /**
     * Country options for the searchable dropdown.
     */
    private function countries()
    {
        return Country::orderBy('name')->pluck('name', 'id');
    }
}
