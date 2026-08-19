<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payslip - {{ $item->employee?->employee_no }} - {{ $payrollRun->period_month }}/{{ $payrollRun->period_year }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
            font-size: 11.5px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .no-print {
            display: flex;
        }

        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .no-print {
                display: none !important;
            }
            .payslip-wrapper {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                background: #ffffff !important;
            }
        }

        .action-bar {
            max-width: 800px;
            margin: 20px auto 14px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #f1f5f9;
        }

        .payslip-wrapper {
            max-width: 800px;
            margin: 0 auto 40px auto;
            background: #ffffff;
            border: none;
            padding: 24px 32px;
            position: relative;
        }

        /* 1. Header Grid */
        .header-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .company-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .company-subtitle {
            font-size: 10.5px;
            color: #475569;
            margin-top: 1px;
        }

        .statement-badge {
            text-align: right;
        }

        .statement-title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .period-text {
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            margin-top: 2px;
        }

        .batch-text {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
            font-family: 'JetBrains Mono', monospace;
        }

        /* 2. Employee Info Box (Clean no-bg, border line top/bottom) */
        .employee-info-box {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 24px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .info-group {
            display: grid;
            grid-template-columns: 125px 1fr;
            gap: 4px;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
        }

        .info-value {
            color: #0f172a;
            font-weight: 700;
        }

        /* 3. Earnings & Deductions Tables (Clean minimalistic list) */
        .statement-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-bottom: 18px;
        }

        .table-section {
            display: flex;
            flex-direction: column;
        }

        .table-header {
            padding: 6px 0;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #0f172a;
        }

        .table-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }

        .table-row-title {
            color: #1e293b;
            font-weight: 600;
        }

        .table-row-desc {
            font-size: 9.5px;
            color: #64748b;
            display: block;
        }

        .table-row-amount {
            font-weight: 700;
            color: #0f172a;
            text-align: right;
        }

        .table-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-top: 1.5px solid #0f172a;
            border-bottom: 1px solid #cbd5e1;
            font-weight: 800;
            font-size: 11.5px;
            margin-top: auto;
        }

        /* 4. Net Take-Home Salary Highlight (Clean underline & double rule) */
        .net-pay-section {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .net-pay-label {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .net-pay-subtext {
            font-size: 10.5px;
            color: #64748b;
            margin-top: 1px;
        }

        .net-pay-amount {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #0f172a;
        }

        /* 5. Employer Contribution Details (Clean text layout) */
        .employer-box {
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            padding: 10px 0;
            margin-bottom: 22px;
        }

        .employer-header {
            font-size: 10px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .employer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .employer-item {
            padding: 0;
        }

        .employer-item-label {
            font-size: 9.5px;
            font-weight: 600;
            color: #64748b;
        }

        .employer-item-amount {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
        }

        /* 6. Signatures */
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 60px;
            margin-top: 24px;
            padding-top: 10px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 36px;
            border-bottom: 1px solid #475569;
            margin-bottom: 5px;
        }

        .signature-label {
            font-size: 9.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        .disclaimer {
            font-size: 9px;
            color: #64748b;
            text-align: center;
            margin-top: 16px;
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on Print) -->
    <div class="action-bar no-print">
        <a href="{{ route('admin.payroll.show', $payrollRun) }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i>
            <span>Back to Payroll Batch</span>
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bx bx-printer"></i>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Payslip Document -->
    <div class="payslip-wrapper">

        <!-- 1. Header & Company Profile -->
        <div class="header-grid">
            <div>
                <div class="company-title">{{ $company->name ?? 'PayFlow Technologies Sdn Bhd' }}</div>
                <div class="company-subtitle">Company Reg No: {{ $company->registration_no ?? '202601009999' }}</div>
                <div class="company-subtitle">{{ $company->address ?? 'Level 28, Menara PayFlow, KLCC, 50088 Kuala Lumpur, Malaysia' }}</div>
                <div class="company-subtitle mono" style="margin-top: 3px; font-size: 9.5px;">
                    KWSP: {{ $company->epf_no ?? '123456789' }} &bull; SOCSO: {{ $company->socso_no ?? 'A1234567B' }} &bull; LHDN (E): {{ $company->tax_no ?? 'E9876543200' }}
                </div>
            </div>

            <div class="statement-badge">
                <div class="statement-title">Confidential Payslip</div>
                <div class="period-text mono">
                    {{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}
                </div>
                <div class="batch-text">
                    Batch: {{ $payrollRun->batch_no }}
                </div>
            </div>
        </div>

        <!-- 2. Employee Profile & Bank Info -->
        <div class="employee-info-box">
            <div>
                <div class="info-group">
                    <span class="info-label">Employee Name:</span>
                    <span class="info-value">{{ $item->employee?->full_name }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Staff ID / No:</span>
                    <span class="info-value mono">{{ $item->employee?->employee_no }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">NRIC / Passport:</span>
                    <span class="info-value mono">{{ $item->employee?->nric_passport }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Department / Unit:</span>
                    <span class="info-value">{{ $item->employee?->department?->name ?? 'Technology & Product' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Designation:</span>
                    <span class="info-value">{{ $item->employee?->designation ?? 'Full-Time Professional' }}</span>
                </div>
            </div>

            <div>
                <div class="info-group">
                    <span class="info-label">KWSP / EPF Member:</span>
                    <span class="info-value mono">{{ $item->employee?->statutoryProfile?->epf_member_no ?? $item->employee?->epf_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">PERKESO / SOCSO:</span>
                    <span class="info-value mono">{{ $item->employee?->statutoryProfile?->socso_member_no ?? $item->employee?->socso_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">LHDN Income Tax No:</span>
                    <span class="info-value mono">{{ $item->employee?->statutoryProfile?->income_tax_no ?? $item->employee?->tax_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Payment Bank / Acct:</span>
                    <span class="info-value mono">{{ $item->employee?->bank_name ?? 'Maybank' }} &bull; {{ $item->employee?->bank_account_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Payment Date:</span>
                    <span class="info-value mono">{{ $payrollRun->payment_date ? date('d/m/Y', strtotime($payrollRun->payment_date)) : date('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Earnings & Deductions Tables (2 Columns) -->
        <div class="statement-grid">
            
            <!-- Earnings -->
            <div class="table-section">
                <div class="table-header">
                    <span>Earnings &amp; Allowances</span>
                    <span class="mono">Amount (MYR)</span>
                </div>
                
                <div class="table-row">
                    <div>
                        <span class="table-row-title">Basic Monthly Salary</span>
                        <span class="table-row-desc">Contractual Base Wage</span>
                    </div>
                    <span class="table-row-amount mono">RM {{ number_format($item->basic_salary, 2) }}</span>
                </div>

                @if($item->allowances_total > 0 && $item->employee?->salaryComponents && $item->employee->salaryComponents->count() > 0)
                    @foreach($item->employee->salaryComponents->where('salaryComponent.type', 'allowance') as $sc)
                        <div class="table-row">
                            <div>
                                <span class="table-row-title">{{ $sc->salaryComponent?->name ?? 'Fixed Allowance' }}</span>
                                <span class="table-row-desc">{{ $sc->notes ?? ($sc->salaryComponent?->is_epf_subject ? 'Statutory-Subject' : 'Tax-Exempt Concession') }}</span>
                            </div>
                            <span class="table-row-amount mono">RM {{ number_format($sc->amount, 2) }}</span>
                        </div>
                    @endforeach
                @elseif($item->allowances_total > 0)
                    <div class="table-row">
                        <div>
                            <span class="table-row-title">Fixed &amp; Custom Allowances</span>
                            <span class="table-row-desc">Taxable Remuneration</span>
                        </div>
                        <span class="table-row-amount mono">RM {{ number_format($item->allowances_total, 2) }}</span>
                    </div>
                @else
                    <div class="table-row">
                        <div>
                            <span class="table-row-title">Fixed Allowances</span>
                            <span class="table-row-desc">None claimable for period</span>
                        </div>
                        <span class="table-row-amount mono" style="color: #64748b;">RM 0.00</span>
                    </div>
                @endif

                @if($item->unpaid_leave_deduction > 0)
                    <div class="table-row" style="background-color: #fff1f2;">
                        <div>
                            <span class="table-row-title" style="color: #e11d48;">Unpaid Leave Deduction (ORP)</span>
                            <span class="table-row-desc">Absence adjustment (Basic / 26 days)</span>
                        </div>
                        <span class="table-row-amount mono" style="color: #e11d48;">- RM {{ number_format($item->unpaid_leave_deduction, 2) }}</span>
                    </div>
                @endif

                <div class="table-total-row">
                    <span>Total Gross Earnings</span>
                    <span class="mono">RM {{ number_format($item->gross_salary, 2) }}</span>
                </div>
            </div>

            <!-- Deductions -->
            <div class="table-section">
                <div class="table-header">
                    <span>Employee Statutory Deductions</span>
                    <span class="mono">Amount (MYR)</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">KWSP / EPF (Employee 11%)</span>
                        <span class="table-row-desc">Third Schedule Statutory</span>
                    </div>
                    <span class="table-row-amount mono">- RM {{ number_format($item->epf_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">PERKESO SOCSO &amp; SKBBK</span>
                        <span class="table-row-desc">Act 4 + 2026 Lindung 24 Jam</span>
                    </div>
                    <span class="table-row-amount mono">- RM {{ number_format($item->socso_employee + $item->skbbk_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">SIP / EIS Employee (0.2%)</span>
                        <span class="table-row-desc">Employment Insurance Act 800</span>
                    </div>
                    <span class="table-row-amount mono">- RM {{ number_format($item->eis_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">LHDN Monthly Tax (PCB)</span>
                        <span class="table-row-desc">Monthly Tax Deduction</span>
                    </div>
                    <span class="table-row-amount mono">- RM {{ number_format($item->pcb_amount, 2) }}</span>
                </div>

                <div class="table-total-row">
                    <span>Total Deductions</span>
                    <span class="mono">- RM {{ number_format($item->total_employee_deductions, 2) }}</span>
                </div>
            </div>

        </div>

        <!-- 4. Net Take-Home Salary Clean Header Section -->
        <div class="net-pay-section">
            <div>
                <div class="net-pay-label">Net Take-Home Pay</div>
                <div class="net-pay-subtext">Direct electronic bank transfer to {{ $item->employee?->bank_name ?? 'Maybank' }} ({{ $item->employee?->bank_account_no ?? '••••' }})</div>
            </div>
            <div class="net-pay-amount mono">
                RM {{ number_format($item->net_salary, 2) }}
            </div>
        </div>

        <!-- 5. Employer Contribution Summary -->
        <div class="employer-box">
            <div class="employer-header">
                <span>Employer Statutory Contributions (Company Cost &bull; Not Deducted from Employee)</span>
                <span class="mono">Total: RM {{ number_format($item->total_employer_contributions, 2) }}</span>
            </div>
            <div class="employer-grid mono">
                <div class="employer-item">
                    <div class="employer-item-label">KWSP / EPF (12-13%)</div>
                    <div class="employer-item-amount">RM {{ number_format($item->epf_employer, 2) }}</div>
                </div>
                <div class="employer-item">
                    <div class="employer-item-label">PERKESO SOCSO (1.75%)</div>
                    <div class="employer-item-amount">RM {{ number_format($item->socso_employer, 2) }}</div>
                </div>
                <div class="employer-item">
                    <div class="employer-item-label">SIP / EIS (0.2%)</div>
                    <div class="employer-item-amount">RM {{ number_format($item->eis_employer, 2) }}</div>
                </div>
                <div class="employer-item">
                    <div class="employer-item-label">HRD Corp Levy (1%)</div>
                    <div class="employer-item-amount">RM {{ number_format($item->gross_salary * 0.01, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- 6. Signatures -->
        <div class="footer-grid">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Authorized Signatory / Payroll Officer</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-label">Employee Signature (Acknowledgment)</div>
            </div>
        </div>

        <div class="disclaimer">
            This is an official computer-generated payslip generated by PayFlow MY Engine conforming to the Malaysian Employment Act 1955 and Statutory Regulations 2026.
        </div>
    </div>

</body>
</html>
