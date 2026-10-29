<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'email',
        'phone',
        'tin_number',
        'address',
        'city',
        'country',
        'currency_code',
        'currency_symbol',
        'logo_url',
        'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'business_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(BusinessLocation::class, 'business_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'business_id')->latestOfMany();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'business_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'business_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'business_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'business_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'business_id');
    }

    public function taxRecords(): HasMany
    {
        return $this->hasMany(TaxRecord::class, 'business_id');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class, 'business_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'business_id');
    }

    /**
     * Get active or primary location.
     */
    public function getPrimaryLocationAttribute(): ?BusinessLocation
    {
        return $this->locations()->where('is_primary', true)->first() ?? $this->locations()->first();
    }
}
