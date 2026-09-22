<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientContactController;
use App\Http\Controllers\ClientPriceController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LanguagePairController;
use App\Http\Controllers\LegalEntityController;
use App\Http\Controllers\ObjectCodeController;
use App\Http\Controllers\QuoteEmailTemplateController;
use App\Http\Controllers\MultiQuoteEmailTemplateController;
use App\Http\Controllers\ClientInvoiceEmailTemplateController;
use App\Http\Controllers\PdfTemplateController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\DefaultTaxController;
use App\Http\Controllers\ClientPortalSettingController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\VendorAgreementController;
use App\Http\Controllers\VendorApplicationController;
use App\Http\Controllers\VendorPortalSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Operations
    Route::resource('clients', ClientController::class)->middleware('screen:clients.view');

    // Client contact persons (client view > Contact persons tab).
    Route::middleware('screen:clients.view')->group(function () {
        Route::get('clients/{client}/contacts', [ClientContactController::class, 'index'])->name('clients.contacts.index');
        Route::get('clients/{client}/contacts/create', [ClientContactController::class, 'create'])->name('clients.contacts.create');
        Route::post('clients/{client}/contacts', [ClientContactController::class, 'store'])->name('clients.contacts.store');
        Route::get('clients/{client}/contacts/{contact}/edit', [ClientContactController::class, 'edit'])->name('clients.contacts.edit');
        Route::put('clients/{client}/contacts/{contact}', [ClientContactController::class, 'update'])->name('clients.contacts.update');
        Route::delete('clients/{client}/contacts/{contact}', [ClientContactController::class, 'destroy'])->name('clients.contacts.destroy');
    });

    // Client prices (Clients > Prices).
    Route::resource('client-prices', ClientPriceController::class)
        ->parameters(['client-prices' => 'clientPrice'])
        ->except(['show'])
        ->middleware('screen:client-prices.view');

    // Settings
    Route::resource('users', UserController::class)->middleware('screen:users.view');
    Route::resource('roles', RoleController::class)->middleware('screen:roles.view');
    Route::resource('legal-entities', LegalEntityController::class)->middleware('screen:legal-entities.view');

    // Language pairs (System values) — custom actions, not a full resource.
    Route::middleware('screen:language-pairs.view')->group(function () {
        Route::get('language-pairs', [LanguagePairController::class, 'index'])->name('language-pairs.index');
        Route::get('language-pairs/create', [LanguagePairController::class, 'create'])->name('language-pairs.create');
        Route::post('language-pairs', [LanguagePairController::class, 'store'])->name('language-pairs.store');
        Route::patch('language-pairs/{languagePair}/toggle', [LanguagePairController::class, 'toggle'])->name('language-pairs.toggle');
        Route::delete('language-pairs/{languagePair}', [LanguagePairController::class, 'destroy'])->name('language-pairs.destroy');
    });

    Route::resource('languages', LanguageController::class)->middleware('screen:languages.view');
    Route::patch('currencies/{currency}/toggle', [CurrencyController::class, 'toggle'])
        ->name('currencies.toggle')->middleware('screen:currencies.view');
    Route::patch('currencies/{currency}/set-system', [CurrencyController::class, 'setSystem'])
        ->name('currencies.set-system')->middleware('screen:currencies.view');
    Route::resource('currencies', CurrencyController::class)->middleware('screen:currencies.view');
    Route::patch('services/{service}/toggle', [ServiceController::class, 'toggle'])
        ->name('services.toggle')->middleware('screen:services.view');
    Route::resource('services', ServiceController::class)->middleware('screen:services.view');

    Route::patch('specializations/{specialization}/toggle', [SpecializationController::class, 'toggle'])
        ->name('specializations.toggle')->middleware('screen:specializations.view');
    Route::resource('specializations', SpecializationController::class)->middleware('screen:specializations.view');

    // Object codes (settings).
    Route::middleware('screen:object-codes.view')->group(function () {
        Route::get('settings/object-codes', [ObjectCodeController::class, 'index'])->name('object-codes.index');
        Route::get('settings/object-codes/prefixes', [ObjectCodeController::class, 'editPrefixes'])->name('object-codes.prefixes.edit');
        Route::put('settings/object-codes/prefixes', [ObjectCodeController::class, 'updatePrefixes'])->name('object-codes.prefixes.update');
        Route::get('settings/object-codes/next-ids', [ObjectCodeController::class, 'editNextIds'])->name('object-codes.next-ids.edit');
        Route::put('settings/object-codes/next-ids', [ObjectCodeController::class, 'updateNextIds'])->name('object-codes.next-ids.update');
        Route::get('settings/object-codes/settings', [ObjectCodeController::class, 'editSettings'])->name('object-codes.settings.edit');
        Route::put('settings/object-codes/settings', [ObjectCodeController::class, 'updateSettings'])->name('object-codes.settings.update');
    });

    // SMTP server (single-record settings).
    Route::middleware('screen:smtp.view')->group(function () {
        Route::get('settings/smtp', [SmtpSettingController::class, 'show'])->name('smtp.show');
        Route::get('settings/smtp/edit', [SmtpSettingController::class, 'edit'])->name('smtp.edit');
        Route::put('settings/smtp', [SmtpSettingController::class, 'update'])->name('smtp.update');
    });
    // Payment methods (settings).
    Route::middleware('screen:payment-methods.view')->group(function () {
        Route::get('settings/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::get('settings/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
        Route::post('settings/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::get('settings/payment-methods/{payment_method}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
        Route::put('settings/payment-methods/{payment_method}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
        Route::patch('settings/payment-methods/{payment_method}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
        Route::delete('settings/payment-methods/{payment_method}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    });

    // Client portal settings (single-record; shared with "Set default payment method").
    Route::middleware('screen:client-portal.view')->group(function () {
        Route::get('settings/client-portal', [ClientPortalSettingController::class, 'show'])->name('client-portal.show');
        Route::get('settings/client-portal/edit', [ClientPortalSettingController::class, 'edit'])->name('client-portal.edit');
        Route::put('settings/client-portal', [ClientPortalSettingController::class, 'update'])->name('client-portal.update');
    });

    // Vendor portal settings (single-record; backs the public application URL).
    Route::middleware('screen:vendor-portal.view')->group(function () {
        Route::get('settings/vendor-portal', [VendorPortalSettingController::class, 'show'])->name('vendor-portal.show');
        Route::get('settings/vendor-portal/edit', [VendorPortalSettingController::class, 'edit'])->name('vendor-portal.edit');
        Route::put('settings/vendor-portal', [VendorPortalSettingController::class, 'update'])->name('vendor-portal.update');
        Route::post('settings/vendor-portal/regenerate-token', [VendorPortalSettingController::class, 'regenerateToken'])->name('vendor-portal.regenerate-token');

        // Vendor agreement (single agreement per portal).
        Route::get('settings/vendor-agreement/create', [VendorAgreementController::class, 'create'])->name('vendor-agreement.create');
        Route::post('settings/vendor-agreement', [VendorAgreementController::class, 'store'])->name('vendor-agreement.store');
        Route::get('settings/vendor-agreement/edit', [VendorAgreementController::class, 'edit'])->name('vendor-agreement.edit');
        Route::put('settings/vendor-agreement', [VendorAgreementController::class, 'update'])->name('vendor-agreement.update');
        Route::delete('settings/vendor-agreement', [VendorAgreementController::class, 'destroy'])->name('vendor-agreement.destroy');
    });

    // Email templates > Quote templates.
    Route::middleware('screen:quote-email-templates.view')->group(function () {
        Route::resource('settings/quote-email-templates', QuoteEmailTemplateController::class)
            ->parameters(['quote-email-templates' => 'quoteEmailTemplate'])
            ->except(['show']);
    });

    // Email templates > Multi-quote templates.
    Route::middleware('screen:multi-quote-email-templates.view')->group(function () {
        Route::resource('settings/multi-quote-email-templates', MultiQuoteEmailTemplateController::class)
            ->parameters(['multi-quote-email-templates' => 'multiQuoteEmailTemplate'])
            ->except(['show']);
    });

    // Email templates > Client invoice templates.
    Route::middleware('screen:client-invoice-email-templates.view')->group(function () {
        Route::resource('settings/client-invoice-email-templates', ClientInvoiceEmailTemplateController::class)
            ->parameters(['client-invoice-email-templates' => 'clientInvoiceEmailTemplate'])
            ->except(['show']);
    });

    // PDF templates (single screen for all object types).
    Route::middleware('screen:pdf-templates.view')->group(function () {
        Route::resource('settings/pdf-templates', PdfTemplateController::class)
            ->parameters(['pdf-templates' => 'pdfTemplate'])
            ->except(['show']);
    });

    // Tax configuration (taxes list + default taxes for clients/vendors).
    Route::middleware('screen:taxes.view')->group(function () {
        Route::resource('settings/taxes', TaxController::class)
            ->parameters(['taxes' => 'tax'])
            ->except(['show']);

        Route::resource('settings/default-taxes', DefaultTaxController::class)
            ->parameters(['default-taxes' => 'defaultTax'])
            ->except(['show']);
    });

    Route::patch('units/{unit}/toggle', [UnitController::class, 'toggle'])
        ->name('units.toggle')->middleware('screen:units.view');
    Route::resource('units', UnitController::class)->middleware('screen:units.view');
});

// Public vendor application form (no auth) — shareable token-based link.
Route::get('vendor-app/create', [VendorApplicationController::class, 'create'])->name('vendor-app.create');

// Public vendor agreement page (no auth) — token-based link.
Route::get('vendor-agreement/view', [VendorAgreementController::class, 'view'])->name('vendor-agreement.view');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
