<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAutopayBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id',
        'format_type',
        'batch_reference_no',
        'file_path',
        'total_records',
        'total_disbursement_amount',
        'status',
        'generated_by',
    ];

    protected $casts = [
        'total_disbursement_amount' => 'decimal:2',
    ];

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
