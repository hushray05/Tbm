<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'category',
        'amount_ugx',
        'expense_date',
        'vendor_name',
        'payment_method',
        'reference_no',
        'description',
        'receipt_url',
        'created_by',
    ];

    protected $casts = [
        'amount_ugx' => 'integer',
        'expense_date' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'UGX ' . number_format($this->amount_ugx);
    }
}
