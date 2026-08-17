<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'basic_salary',
        'allowances_total',
        'overtime_total',
        'bonus_amount',
        'gross_salary',
        'unpaid_leave_deduction',
        'epf_subject_wages',
        'socso_subject_wages',
        'eis_subject_wages',
        'pcb_subject_wages',
        'hrd_subject_wages',
        'epf_employee',
        'socso_employee',
        'skbbk_employee',
        'eis_employee',
        'pcb_amount',
        'zakat_amount',
        'other_deductions_total',
        'total_employee_deductions',
        'epf_employer',
        'socso_employer',
        'eis_employer',
        'hrd_levy_employer',
        'total_employer_contributions',
        'net_salary',
        'payslip_token',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances_total' => 'decimal:2',
        'overtime_total' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'unpaid_leave_deduction' => 'decimal:2',
        'epf_employee' => 'decimal:2',
        'socso_employee' => 'decimal:2',
        'skbbk_employee' => 'decimal:2',
        'eis_employee' => 'decimal:2',
        'pcb_amount' => 'decimal:2',
        'zakat_amount' => 'decimal:2',
        'total_employee_deductions' => 'decimal:2',
        'epf_employer' => 'decimal:2',
        'socso_employer' => 'decimal:2',
        'eis_employer' => 'decimal:2',
        'hrd_levy_employer' => 'decimal:2',
        'total_employer_contributions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function breakdowns()
    {
        return $this->hasMany(PayrollItemBreakdown::class);
    }
}
