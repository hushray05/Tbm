<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'business_id',
        'type', // opening, purchase, sale, adjustment, return, pos
        'reference',
        'quantity_change',
        'balance_after',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'balance_after' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'purchase' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'sale', 'pos' => 'bg-blue-100 text-blue-800 border-blue-200',
            'return' => 'bg-purple-100 text-purple-800 border-purple-200',
            'adjustment' => 'bg-amber-100 text-amber-800 border-amber-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
