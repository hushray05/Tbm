<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'staff_id',
        'payroll_period',
        'basic_salary_ugx',
        'allowances_ugx',
        'gross_pay_ugx',
        'nssf_employee_ugx',
        'nssf_employer_ugx',
        'paye_tax_ugx',
        'other_deductions_ugx',
        'net_salary_ugx',
        'payment_status',
        'payment_date',
        'payment_method',
        'reference_no',
    ];

    protected $casts = [
        'basic_salary_ugx' => 'integer',
        'allowances_ugx' => 'integer',
        'gross_pay_ugx' => 'integer',
        'nssf_employee_ugx' => 'integer',
        'nssf_employer_ugx' => 'integer',
        'paye_tax_ugx' => 'integer',
        'other_deductions_ugx' => 'integer',
        'net_salary_ugx' => 'integer',
        'payment_date' => 'date',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Compute Uganda statutory tax (PAYE) based on monthly gross pay in UGX.
     * Uganda URA Monthly Brackets:
     * - First 235,000: 0%
     * - Next 100,000 (235,001 - 335,000): 10%
     * - Next 765,000 (335,001 - 1,100,000): 20%
     * - Next 9,000,000 (1,100,001 - 10,000,000): 30%
     * - Above 10,000,000: 30% + 10% additional = 40% on excess
     */
    public static function calculateUgandaPAYE(int|float $chargeableIncome): int
    {
        $income = max(0, $chargeableIncome);
        $tax = 0;

        if ($income <= 235000) {
            return 0;
        }

        if ($income > 235000 && $income <= 335000) {
            $tax += ($income - 235000) * 0.10;
        } elseif ($income > 335000 && $income <= 1100000) {
            $tax += (100000 * 0.10) + (($income - 335000) * 0.20);
        } elseif ($income > 1100000 && $income <= 10000000) {
            $tax += (100000 * 0.10) + (765000 * 0.20) + (($income - 1100000) * 0.30);
        } else {
            // Above 10,000,000
            $tax += (100000 * 0.10) + (765000 * 0.20) + (8900000 * 0.30) + (($income - 10000000) * 0.40);
        }

        return (int) round($tax);
    }
}
