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
            margin: 12mm 15mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 12px;
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
                background-color: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .payslip-wrapper {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }

        .action-bar {
            max-width: 800px;
            margin: 20px auto 12px auto;
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
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            padding: 32px 36px;
            position: relative;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            font-weight: 900;
            color: rgba(79, 70, 229, 0.03);
            text-transform: uppercase;
            letter-spacing: 8px;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .header-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }

        .company-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .company-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .statement-badge {
            text-align: right;
        }

        .statement-title {
            font-size: 15px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .period-pill {
            display: inline-block;
            background-color: #e0e7ff;
            color: #3730a3;
            font-weight: 700;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 4px;
        }

        .employee-info-box {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 22px;
        }

        .info-group {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 4px;
            margin-bottom: 4px;
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

        /* 2-Column Earnings & Deductions Tables */
        .statement-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .table-section {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-header {
            background-color: #f1f5f9;
            padding: 10px 14px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header.earnings {
            color: #0f172a;
            border-top: 3px solid #4f46e5;
        }

        .table-header.deductions {
            color: #0f172a;
            border-top: 3px solid #e11d48;
        }

        .table-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11.5px;
        }

        .table-row:nth-child(even) {
            background-color: #fafbfc;
        }

        .table-row-title {
            color: #334155;
            font-weight: 600;
        }

        .table-row-desc {
            font-size: 10px;
            color: #94a3b8;
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
            padding: 11px 14px;
            background-color: #f8fafc;
            border-top: 2px solid #e2e8f0;
            font-weight: 800;
            font-size: 12px;
        }

        /* Net Pay Hero Box */
        .net-pay-card {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(6, 95, 70, 0.15);
        }

        .net-pay-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .net-pay-subtext {
            font-size: 11px;
            color: #a7f3d0;
            margin-top: 2px;
        }

        .net-pay-amount {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        /* Employer Contribution Details */
        .employer-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }

        .employer-header {
            font-size: 11px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .employer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            text-align: center;
        }

        .employer-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 10px;
        }

        .employer-item-label {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
        }

        .employer-item-amount {
            font-size: 12px;
            font-weight: 800;
            color: #334155;
            margin-top: 2px;
        }

        /* Footer & Signatures */
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #cbd5e1;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            height: 40px;
            border-bottom: 1px solid #94a3b8;
            margin-bottom: 6px;
        }

        .signature-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .disclaimer {
            font-size: 9.5px;
            color: #94a3b8;
            text-align: center;
            margin-top: 20px;
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
        <div class="watermark">PAYFLOW CONFIDENTIAL</div>

        <!-- 1. Header & Company Profile -->
        <div class="header-grid">
            <div>
                <div class="company-title">{{ $company->name ?? 'PayFlow Technologies Sdn Bhd' }}</div>
                <div class="company-subtitle">Company Reg No: {{ $company->registration_no ?? '202601009999' }}</div>
                <div class="company-subtitle">{{ $company->address ?? 'Level 28, Menara PayFlow, KLCC, 50088 Kuala Lumpur, Malaysia' }}</div>
                <div class="company-subtitle" style="margin-top: 4px; font-family: monospace; font-size: 10px;">
                    KWSP: {{ $company->epf_no ?? '123456789' }} &bull; SOCSO: {{ $company->socso_no ?? 'A1234567B' }} &bull; LHDN (E): {{ $company->tax_no ?? 'E9876543200' }}
                </div>
            </div>

            <div class="statement-badge">
                <div class="statement-title">Confidential Payslip</div>
                <div class="period-pill mono">
                    {{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}
                </div>
                <div style="font-size: 10px; color: #64748b; margin-top: 5px; font-family: monospace;">
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
                    <span class="info-value mono">{{ $item->employee?->epf_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">PERKESO / SOCSO:</span>
                    <span class="info-value mono">{{ $item->employee?->socso_no ?? '—' }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">LHDN Income Tax No:</span>
                    <span class="info-value mono">{{ $item->employee?->tax_no ?? '—' }}</span>
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
                <div class="table-header earnings">
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

                @if($item->allowances_total > 0)
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
                        <span class="table-row-amount mono" style="color: #94a3b8;">RM 0.00</span>
                    </div>
                @endif

                <div class="table-total-row">
                    <span>Total Gross Earnings</span>
                    <span class="mono" style="color: #4f46e5;">RM {{ number_format($item->gross_salary, 2) }}</span>
                </div>
            </div>

            <!-- Deductions -->
            <div class="table-section">
                <div class="table-header deductions">
                    <span>Employee Statutory Deductions</span>
                    <span class="mono">Amount (MYR)</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">KWSP / EPF (Employee 11%)</span>
                        <span class="table-row-desc">Third Schedule Statutory</span>
                    </div>
                    <span class="table-row-amount mono" style="color: #e11d48;">- RM {{ number_format($item->epf_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">PERKESO SOCSO &amp; SKBBK</span>
                        <span class="table-row-desc">Act 4 + 2026 Lindung 24 Jam</span>
                    </div>
                    <span class="table-row-amount mono" style="color: #e11d48;">- RM {{ number_format($item->socso_employee + $item->skbbk_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">SIP / EIS Employee (0.2%)</span>
                        <span class="table-row-desc">Employment Insurance Act 800</span>
                    </div>
                    <span class="table-row-amount mono" style="color: #e11d48;">- RM {{ number_format($item->eis_employee, 2) }}</span>
                </div>

                <div class="table-row">
                    <div>
                        <span class="table-row-title">LHDN Monthly Tax (PCB)</span>
                        <span class="table-row-desc">Monthly Tax Deduction</span>
                    </div>
                    <span class="table-row-amount mono" style="color: #e11d48;">- RM {{ number_format($item->pcb_amount, 2) }}</span>
                </div>

                <div class="table-total-row">
                    <span>Total Deductions</span>
                    <span class="mono" style="color: #e11d48;">- RM {{ number_format($item->total_employee_deductions, 2) }}</span>
                </div>
            </div>

        </div>

        <!-- 4. Net Take-Home Salary Hero Box -->
        <div class="net-pay-card">
            <div>
                <div class="net-pay-label">Net Take-Home Pay</div>
                <div class="net-pay-subtext">Direct electronic bank transfer to {{ $item->employee?->bank_name ?? 'Maybank' }} ({{ $item->employee?->bank_account_no ?? '••••' }})</div>
            </div>
            <div class="net-pay-amount mono">
                RM {{ number_format($item->net_salary, 2) }}
            </div>
        </div>

        <!-- 5. Employer Contribution Summary (For Reference) -->
        <div class="employer-box">
            <div class="employer-header">
                <span>Employer Statutory Contributions (Company Cost &bull; Not Deducted from Employee)</span>
                <span class="mono" style="color: #4f46e5;">Total: RM {{ number_format($item->total_employer_contributions, 2) }}</span>
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
            This is a computer-generated official payslip generated by PayFlow MY Engine conforming to the Malaysian Employment Act 1955 and Statutory Regulations 2026. No physical signature is required for electronic verification.
        </div>
    </div>

</body>
</html>
