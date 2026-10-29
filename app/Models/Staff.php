<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'business_id',
        'employee_id',
        'name',
        'email',
        'phone',
        'department',
        'designation',
        'basic_salary_ugx',
        'allowances_ugx',
        'tin_number',
        'nssf_number',
        'payment_method',
        'bank_name',
        'account_number',
        'mobile_money_number',
        'is_active',
    ];

    protected $casts = [
        'basic_salary_ugx' => 'integer',
        'allowances_ugx' => 'integer',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'staff_id');
    }

    public function getGrossSalaryAttribute(): int
    {
        return $this->basic_salary_ugx + $this->allowances_ugx;
    }
}
