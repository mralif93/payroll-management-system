<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_no',
        'epf_no',
        'socso_no',
        'tax_no',
        'hrd_no',
        'bank_name',
        'bank_account_no',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function payrollRuns()
    {
        return $this->hasMany(PayrollRun::class);
    }
}
