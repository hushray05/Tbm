<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'sku',
        'description',
        'price_cents',
        'cost_price_cents',
        'stock_quantity',
        'category',
        'vat_rate',
        'is_active',
        'image_url',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'cost_price_cents' => 'integer',
        'stock_quantity' => 'integer',
        'vat_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->latest('id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->price_cents);
    }

    public function getFormattedCostPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->cost_price_cents ?: 0);
    }

    public function getIsLowStockAttribute(): bool
    {
        $threshold = config('business.low_stock_threshold', 10);
        return $this->stock_quantity <= $threshold;
    }

    /**
     * Adjust stock and log an automatic audit movement record in inventory history.
     */
    public function adjustStock(int $quantityChange, string $type, ?string $reference = null, ?string $notes = null, ?int $userId = null): StockMovement
    {
        $newBalance = max(0, $this->stock_quantity + $quantityChange);
        $this->update(['stock_quantity' => $newBalance]);

        return StockMovement::create([
            'product_id' => $this->id,
            'business_id' => $this->business_id,
            'type' => $type,
            'reference' => $reference,
            'quantity_change' => $quantityChange,
            'balance_after' => $newBalance,
            'notes' => $notes,
            'created_by' => $userId ?? (auth()->id() ?? null),
        ]);
    }
}
