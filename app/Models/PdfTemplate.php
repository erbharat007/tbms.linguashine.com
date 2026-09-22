<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfTemplate extends Model
{
    protected $fillable = [
        'object_type',
        'name',
        'page_size',
        'page_orientation',
        'margin_top',
        'margin_bottom',
        'locale',
        'header',
        'footer',
        'common_note',
        'include_cat_log',
    ];

    protected $casts = [
        'margin_top' => 'integer',
        'margin_bottom' => 'integer',
        'include_cat_log' => 'boolean',
    ];

    /**
     * Object types a PDF template can apply to (value => label).
     *
     * @return array<string, string>
     */
    public static function objectTypes(): array
    {
        return [
            'client_invoice' => 'Client invoice',
            'vendor_invoice' => 'Vendor invoice',
            'quote' => 'Quote',
            'job_po' => 'Job PO',
        ];
    }

    /**
     * Page size options (value => label).
     *
     * @return array<string, string>
     */
    public static function pageSizes(): array
    {
        return [
            'A4' => 'A4 (210 x 297 mm, 8.27 x 11.69 inches)',
            'Letter' => 'Letter (215.9 x 279.4 mm, 8.5 x 11 inches)',
        ];
    }

    /**
     * Page orientation options (value => label).
     *
     * @return array<string, string>
     */
    public static function orientations(): array
    {
        return [
            'portrait' => 'Portrait',
            'landscape' => 'Landscape',
        ];
    }

    /**
     * Locale options (value => label). Curated list of common invoicing
     * locales; affects number/date formatting on the generated PDF.
     *
     * @return array<string, string>
     */
    public static function locales(): array
    {
        return [
            'en_US' => 'English (United States)',
            'en_GB' => 'English (United Kingdom)',
            'en_CA' => 'English (Canada)',
            'en_AU' => 'English (Australia)',
            'en_IN' => 'English (India)',
            'fr_FR' => 'French (France)',
            'fr_CA' => 'French (Canada)',
            'de_DE' => 'German (Germany)',
            'de_AT' => 'German (Austria)',
            'de_CH' => 'German (Switzerland)',
            'es_ES' => 'Spanish (Spain)',
            'es_MX' => 'Spanish (Mexico)',
            'es_US' => 'Spanish (United States)',
            'it_IT' => 'Italian (Italy)',
            'pt_PT' => 'Portuguese (Portugal)',
            'pt_BR' => 'Portuguese (Brazil)',
            'nl_NL' => 'Dutch (Netherlands)',
            'nl_BE' => 'Dutch (Belgium)',
            'ru_RU' => 'Russian (Russia)',
            'uk_UA' => 'Ukrainian (Ukraine)',
            'pl_PL' => 'Polish (Poland)',
            'ro_RO' => 'Romanian (Romania)',
            'tr_TR' => 'Turkish (Türkiye)',
            'zh_CN' => 'Chinese (Simplified, China)',
            'zh_TW' => 'Chinese (Traditional, Taiwan)',
            'ja_JP' => 'Japanese (Japan)',
            'hi_IN' => 'Hindi (India)',
            'ar_AE' => 'Arabic (United Arab Emirates)',
        ];
    }

    /**
     * Human-readable labels for the index/show screens.
     */
    public function getObjectTypeLabelAttribute(): string
    {
        return static::objectTypes()[$this->object_type] ?? $this->object_type;
    }

    public function getPageSizeLabelAttribute(): string
    {
        return static::pageSizes()[$this->page_size] ?? $this->page_size;
    }

    public function getPageOrientationLabelAttribute(): string
    {
        return static::orientations()[$this->page_orientation] ?? ucfirst($this->page_orientation);
    }

    public function getLocaleLabelAttribute(): string
    {
        return static::locales()[$this->locale] ?? $this->locale;
    }
}
