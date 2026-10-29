<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'product_id',
        'item_name',
        'quantity',
        'unit_cost_ugx',
        'total_cost_ugx',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost_ugx' => 'integer',
        'total_cost_ugx' => 'integer',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
