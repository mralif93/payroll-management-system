<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatutoryProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'epf_member_no',
        'epf_rate_type',
        'epf_employee_custom_rate',
        'epf_employer_custom_rate',
        'socso_member_no',
        'socso_category',
        'is_eis_contributed',
        'is_skbbk_contributed',
        'income_tax_no',
        'tax_category',
        'is_tax_resident',
        'number_of_children',
        'is_disabled',
        'spouse_is_disabled',
        'monthly_zakat_amount',
        'total_tp1_relief_amount',
    ];

    protected $casts = [
        'is_eis_contributed' => 'boolean',
        'is_skbbk_contributed' => 'boolean',
        'is_tax_resident' => 'boolean',
        'is_disabled' => 'boolean',
        'spouse_is_disabled' => 'boolean',
        'monthly_zakat_amount' => 'decimal:2',
        'total_tp1_relief_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
