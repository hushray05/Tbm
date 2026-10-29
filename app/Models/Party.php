<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'company',
        'email',
        'phone',
        'tin_number',
        'address',
        'city',
        'type', // customer, vendor
        'balance_ugx',
    ];

    protected $casts = [
        'balance_ugx' => 'integer',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
