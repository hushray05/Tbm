<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'industry',
        'website',
        'logo_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Deterministic fallback logo (first letters of the company name) when no image is set.
     */
    public function getMonogramAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));

        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1));
        }

        return strtoupper(mb_substr($this->name, 0, 2));
    }

    /**
     * Autogenerate a unique slug from the company name.
     */
    protected static function booted(): void
    {
        static::saving(function (Company $company) {
            if (empty($company->slug) && $company->name) {
                $company->slug = Str::slug($company->name);
            }
        });
    }
}