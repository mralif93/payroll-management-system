<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'full_name',
        'relationship',
        'birth_date',
        'is_studying_higher_education',
        'is_disabled',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_studying_higher_education' => 'boolean',
        'is_disabled' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
