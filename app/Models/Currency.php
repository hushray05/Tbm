<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_active',
        'country',
        'flag',
        'format',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean',
    ];

    /**
     * Map of currency codes to ISO 3166-1 alpha-2 country codes
     * used for rendering real flag images via flagcdn.com
     */
    protected static array $currencyToCountry = [
        'USD' => 'us', 'EUR' => 'eu', 'GBP' => 'gb',
        'NGN' => 'ng', 'ZAR' => 'za', 'EGP' => 'eg',
        'GHS' => 'gh', 'KES' => 'ke', 'MAD' => 'ma',
        'XOF' => 'sn', 'XAF' => 'cm', 'TND' => 'tn',
        'UGX' => 'ug', 'DZD' => 'dz', 'AOA' => 'ao',
        'BWP' => 'bw', 'BIF' => 'bi', 'CVE' => 'cv',
        'KMF' => 'km', 'CDF' => 'cd', 'DJF' => 'dj',
        'ERN' => 'er', 'SZL' => 'sz', 'ETB' => 'et',
        'GMD' => 'gm', 'GNF' => 'gn', 'LSL' => 'ls',
        'LRD' => 'lr', 'LYD' => 'ly', 'MGA' => 'mg',
        'MWK' => 'mw', 'MRU' => 'mr', 'MUR' => 'mu',
        'MZN' => 'mz', 'NAD' => 'na', 'RWF' => 'rw',
        'STN' => 'st', 'SCR' => 'sc', 'SLE' => 'sl',
        'SOS' => 'so', 'SSP' => 'ss', 'SDG' => 'sd',
        'TZS' => 'tz', 'ZMW' => 'zm', 'ZWL' => 'zw',
        'JPY' => 'jp', 'CNY' => 'cn', 'INR' => 'in',
        'CAD' => 'ca', 'AUD' => 'au', 'CHF' => 'ch',
        'AED' => 'ae', 'SAR' => 'sa', 'BRL' => 'br',
    ];

    /**
     * Get the ISO alpha-2 country code for flag rendering.
     */
    public function getCountryCodeAttribute(): string
    {
        return static::$currencyToCountry[strtoupper($this->code)] ?? 'un';
    }

    /**
     * Get the flagcdn.com image URL for a real country flag (40px wide).
     */
    public function getFlagImageUrlAttribute(): string
    {
        $iso = $this->country_code;
        // Use EU flag for EUR, UN flag fallback for unknowns
        return "https://flagcdn.com/w40/{$iso}.png";
    }

    /**
     * Get a 2x (80px) retina version of the flag image.
     */
    public function getFlagImageUrl2xAttribute(): string
    {
        $iso = $this->country_code;
        return "https://flagcdn.com/w80/{$iso}.png";
    }
}
