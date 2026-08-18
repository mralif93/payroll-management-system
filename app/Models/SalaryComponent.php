<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'is_epf_subject',
        'is_socso_subject',
        'is_eis_subject',
        'is_pcb_subject',
        'is_hrd_subject',
        'is_taxable_benefit',
        'is_active',
    ];

    protected $casts = [
        'is_epf_subject' => 'boolean',
        'is_socso_subject' => 'boolean',
        'is_eis_subject' => 'boolean',
        'is_pcb_subject' => 'boolean',
        'is_hrd_subject' => 'boolean',
        'is_taxable_benefit' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employeeComponents()
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }

    public function employeeSalaryComponents()
    {
        return $this->hasMany(EmployeeSalaryComponent::class);
    }
}
