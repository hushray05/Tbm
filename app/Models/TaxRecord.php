<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'tax_type',
        'period',
        'taxable_amount_ugx',
        'tax_rate_percent',
        'tax_amount_ugx',
        'status',
        'due_date',
        'filing_date',
        'ura_prn',
    ];

    protected $casts = [
        'taxable_amount_ugx' => 'integer',
        'tax_amount_ugx' => 'integer',
        'tax_rate_percent' => 'decimal:2',
        'due_date' => 'date',
        'filing_date' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
