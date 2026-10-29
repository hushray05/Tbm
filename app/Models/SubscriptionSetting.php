<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SubscriptionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value with fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("sub_setting_{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (!$setting) {
                return $default;
            }

            return match ($setting->type) {
                'integer' => (int) $setting->value,
                'boolean' => (bool) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    /**
     * Set a setting value and invalidate cache.
     */
    public static function set(string $key, mixed $value, string $type = 'string', ?string $description = null): void
    {
        $stringValue = is_array($value) ? json_encode($value) : (string) $value;

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'description' => $description,
            ]
        );

        Cache::forget("sub_setting_{$key}");
    }
}
