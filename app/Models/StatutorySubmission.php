<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutorySubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id',
        'statutory_body',
        'submission_type',
        'file_path',
        'total_payable_amount',
        'receipt_no',
        'status',
        'exported_by',
    ];

    protected $casts = [
        'total_payable_amount' => 'decimal:2',
    ];

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function exporter()
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
