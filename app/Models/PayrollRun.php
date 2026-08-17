<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'batch_no',
        'period_year',
        'period_month',
        'cutoff_date',
        'payment_date',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'total_headcount',
        'total_gross_amount',
        'total_statutory_employee',
        'total_statutory_employer',
        'total_net_disbursement',
        'remarks',
    ];

    protected $casts = [
        'cutoff_date' => 'date',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
        'total_gross_amount' => 'decimal:2',
        'total_statutory_employee' => 'decimal:2',
        'total_statutory_employer' => 'decimal:2',
        'total_net_disbursement' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function bankBatches()
    {
        return $this->hasMany(BankAutopayBatch::class);
    }

    public function statutorySubmissions()
    {
        return $this->hasMany(StatutorySubmission::class);
    }
}
