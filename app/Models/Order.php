<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'order_number',
        'type', // sales_order, purchase_order
        'party_name',
        'total_amount_ugx',
        'status',
        'order_date',
        'notes',
        'image_url',
    ];

    protected $casts = [
        'total_amount_ugx' => 'integer',
        'order_date' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
