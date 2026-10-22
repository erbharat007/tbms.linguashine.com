<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\LanguagePair;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemValuesController extends Controller
{
    /**
     * Display the System Values overview hub.
     */
    public function index(): View
    {
        $stats = [
            'language_pairs' => [
                'total' => LanguagePair::count(),
                'active' => LanguagePair::where('is_enabled', true)->count(),
                'route' => route('language-pairs.index'),
                'icon' => 'fas fa-language',
                'color' => 'info',
            ],
            'services' => [
                'total' => Service::count(),
                'active' => Service::where('is_enabled', true)->count(),
                'route' => route('services.index'),
                'icon' => 'fas fa-cogs',
                'color' => 'success',
            ],
            'specializations' => [
                'total' => Specialization::count(),
                'active' => Specialization::where('is_enabled', true)->count(),
                'route' => route('specializations.index'),
                'icon' => 'fas fa-certificate',
                'color' => 'warning',
            ],
            'units' => [
                'total' => Unit::count(),
                'active' => Unit::where('is_enabled', true)->count(),
                'route' => route('units.index'),
                'icon' => 'fas fa-ruler-combined',
                'color' => 'primary',
            ],
            'currencies' => [
                'total' => Currency::count(),
                'active' => Currency::where('is_enabled', true)->count(),
                'system' => Currency::where('is_system', true)->first()?->code ?? 'USD',
                'route' => route('currencies.index'),
                'icon' => 'fas fa-money-bill-wave',
                'color' => 'teal',
            ],
            'languages' => [
                'total' => Language::count(),
                'route' => route('languages.index'),
                'icon' => 'fas fa-globe',
                'color' => 'secondary',
            ],
        ];

        return view('settings.system-values.index', compact('stats'));
    }
}
