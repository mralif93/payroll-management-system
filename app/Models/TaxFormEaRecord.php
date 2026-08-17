<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxFormEaRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tax_year',
        'serial_no',
        'employer_e_no',
        'gross_salary_wages',
        'fees_commission_bonus',
        'gratuity_amount',
        'benefits_in_kind',
        'value_of_living_accomodation',
        'refund_from_unapproved_fund',
        'compensation_for_loss_of_employment',
        'pension_annuities',
        'total_pcb_mtd',
        'total_cp38_deductions',
        'total_zakat_paid',
        'total_tp1_reliefs_claimed',
        'total_epf_employee',
        'total_socso_employee',
        'total_eis_employee',
        'tax_exempt_allowances_total',
        'pdf_path',
        'is_published_to_employee',
    ];

    protected $casts = [
        'gross_salary_wages' => 'decimal:2',
        'fees_commission_bonus' => 'decimal:2',
        'total_pcb_mtd' => 'decimal:2',
        'total_epf_employee' => 'decimal:2',
        'total_socso_employee' => 'decimal:2',
        'total_eis_employee' => 'decimal:2',
        'is_published_to_employee' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
