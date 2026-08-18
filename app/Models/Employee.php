<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id',
        'employee_no',
        'full_name',
        'nric_passport',
        'citizenship',
        'gender',
        'birth_date',
        'joined_date',
        'resigned_date',
        'employment_status',
        'employment_type',
        'basic_salary',
        'designation',
        'bank_name',
        'bank_account_no',
        'email',
        'phone_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'joined_date' => 'date',
        'resigned_date' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function statutoryProfile()
    {
        return $this->hasOne(EmployeeStatutoryProfile::class);
    }

    public function dependents()
    {
        return $this->hasMany(EmployeeDependent::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }

    public function payrollItems()
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function taxFormEaRecords()
    {
        return $this->hasMany(TaxFormEaRecord::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(EmployeeLeaveBalance::class);
    }
}
