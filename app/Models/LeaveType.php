<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_paid',
        'default_days_per_year',
        'color',
        'description',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'default_days_per_year' => 'integer',
    ];

    public function applications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function balances()
    {
        return $this->hasMany(EmployeeLeaveBalance::class);
    }
}
