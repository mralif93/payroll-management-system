<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BORANG EA (C.P.8A) - {{ $record->serial_no }} - {{ $record->tax_year }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
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
            font-size: 11px;
            line-height: 1.35;
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
            .ea-wrapper {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
                background: #ffffff !important;
            }
        }

        .action-bar {
            max-width: 840px;
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

        .ea-wrapper {
            max-width: 840px;
            margin: 0 auto 30px auto;
            background: #ffffff;
            border: 1.5px solid #0f172a;
            padding: 24px 28px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .lhdn-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lhdn-subtitle {
            font-size: 10px;
            color: #475569;
            margin-top: 2px;
        }

        .badge-ea {
            border: 1.5px solid #0f172a;
            padding: 6px 14px;
            text-align: center;
            font-weight: 800;
            background: #f8fafc;
        }

        .section-header {
            background-color: #0f172a;
            color: #ffffff;
            padding: 4px 8px;
            font-weight: 800;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            margin-bottom: 6px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 8px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 10.5px;
        }

        .data-label {
            color: #475569;
            font-weight: 600;
        }

        .data-value {
            font-weight: 700;
            color: #0f172a;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 10.5px;
        }

        .table-data th, .table-data td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
        }

        .table-data th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: left;
        }

        .table-data td.amount {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
        }

        .declaration-box {
            margin-top: 14px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            font-size: 9.5px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <!-- Action Bar -->
    <div class="action-bar no-print">
        <a href="{{ route('admin.tax-ea.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i>
            <span>Back to EA Compiler</span>
        </a>
        <button type="button" onclick="window.print()" class="btn btn-primary">
            <i class="bx bx-printer"></i>
            <span>Print Official Form EA (PDF)</span>
        </button>
    </div>

    <!-- Official LHDN Borang EA Statement -->
    <div class="ea-wrapper">
        
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="vertical-align: top; width: 70%;">
                    <div class="lhdn-title">LEMBAGA HASIL DALAM NEGERI MALAYSIA</div>
                    <div class="lhdn-subtitle font-bold">PENYATA SARAAN DARIPADA PENGGAJIAN BAGI TAHUN BERAKHIR 31 DISEMBER {{ $record->tax_year }}</div>
                    <div class="lhdn-subtitle">BORANG EA [SEKSYEN 83(1A) AKTA CUKAI PENDAPATAN 1967] (C.P.8A - Pin. 2024/2026)</div>
                </td>
                <td style="vertical-align: top; text-align: right; width: 30%;">
                    <div class="badge-ea">
                        <div style="font-size: 16px;">BORANG EA</div>
                        <div class="mono" style="font-size: 11px; margin-top: 2px;">TAHUN: {{ $record->tax_year }}</div>
                        <div class="mono" style="font-size: 9px; color: #64748b; margin-top: 2px;">{{ $record->serial_no }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Employer & Employee Details -->
        <div class="grid-2">
            <div>
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64748b; margin-bottom: 4px;">Maklumat Majikan (Employer)</div>
                <div class="data-row">
                    <span class="data-label">Nama Majikan:</span>
                    <span class="data-value">{{ $company->name ?? 'PayFlow Technologies Sdn Bhd' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">No. Majikan (No. E):</span>
                    <span class="data-value mono">{{ $company->tax_no ?? 'E 9876543200' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">No. Pendaftaran Syarikat:</span>
                    <span class="data-value mono">{{ $company->registration_no ?? '202601009999' }}</span>
                </div>
            </div>

            <div>
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #64748b; margin-bottom: 4px;">BAHAGIAN A: Butiran Pekerja (Employee)</div>
                <div class="data-row">
                    <span class="data-label">Nama Penuh:</span>
                    <span class="data-value">{{ $record->employee?->full_name }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">No. Kad Pengenalan / Pasport:</span>
                    <span class="data-value mono">{{ $record->employee?->nric_passport }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">No. Cukai Pendapatan:</span>
                    <span class="data-value mono">{{ $record->employee?->statutoryProfile?->income_tax_no ?? '—' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Jawatan / No. Pekerja:</span>
                    <span class="data-value">{{ $record->employee?->designation }} ({{ $record->employee?->employee_no }})</span>
                </div>
            </div>
        </div>

        <!-- Section B: Employment Income -->
        <div class="section-header">BAHAGIAN B: PENDAPATAN PENGGAJIAN, MANFAAT DAN TEMPAT KEDIAMAN</div>
        <table class="table-data">
            <tr>
                <td style="width: 75%;">1. (a) Gaji kasar, upah, komisen, elaun, lebih masa dan perkuisit (Gross Salary &amp; Allowances)</td>
                <td class="amount">RM {{ number_format($record->gross_salary_wages, 2) }}</td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;(b) Yuran, ganjaran, pampasan atau bonus tahunan (Bonus / Gratuity)</td>
                <td class="amount">RM {{ number_format($record->fees_commission_bonus, 2) }}</td>
            </tr>
            <tr>
                <td>2. Manfaat berupa barangan (Benefits-In-Kind - BIK)</td>
                <td class="amount">RM 0.00</td>
            </tr>
            <tr>
                <td>3. Nilai tempat kediaman disediakan (Value of Living Accommodation - VOLA)</td>
                <td class="amount">RM 0.00</td>
            </tr>
            <tr style="background-color: #f8fafc; font-weight: 800;">
                <td>JUMLAH PENDAPATAN KASAR TAHUNAN (TOTAL GROSS INCOME)</td>
                <td class="amount">RM {{ number_format($record->gross_salary_wages + $record->fees_commission_bonus, 2) }}</td>
            </tr>
        </table>

        <!-- Section D: Total Deductions -->
        <div class="section-header">BAHAGIAN D: JUMLAH POTONGAN (TOTAL TAX &amp; STATUTORY DEDUCTIONS)</div>
        <table class="table-data">
            <tr>
                <td style="width: 75%;">1. Potongan Cukai Bulanan (PCB / MTD) yang dibayar kepada LHDNM</td>
                <td class="amount" style="color: #e11d48;">RM {{ number_format($record->total_pcb_mtd, 2) }}</td>
            </tr>
            <tr>
                <td>2. Arahan Potongan CP38 (CP38 Court / Arrears Deductions)</td>
                <td class="amount">RM 0.00</td>
            </tr>
            <tr>
                <td>3. Zakat yang dibayar melalui potongan gaji</td>
                <td class="amount">RM {{ number_format($record->total_zakat_paid, 2) }}</td>
            </tr>
        </table>

        <!-- Section E: Approved Funds -->
        <div class="section-header">BAHAGIAN E: CARUMAN KEPADA KUMPULAN WANG SIMPANAN &amp; KESELAMATAN SOSIAL</div>
        <table class="table-data">
            <tr>
                <td style="width: 75%;">1. Kumpulan Wang Simpanan Pekerja (KWSP / EPF Syer Pekerja) - [No. Ahli: {{ $record->employee?->statutoryProfile?->epf_member_no ?? '—' }}]</td>
                <td class="amount">RM {{ number_format($record->total_epf_employee, 2) }}</td>
            </tr>
            <tr>
                <td>2. Pertubuhan Keselamatan Sosial (PERKESO / SOCSO &amp; SIP EIS) - [No. Keselamatan Sosial: {{ $record->employee?->statutoryProfile?->socso_member_no ?? '—' }}]</td>
                <td class="amount">RM {{ number_format($record->total_socso_employee + $record->total_eis_employee, 2) }}</td>
            </tr>
        </table>

        <!-- Section F: Employer Declaration -->
        <div class="section-header">BAHAGIAN F: AKUAN MAJIKAN (EMPLOYER DECLARATION)</div>
        <div class="declaration-box">
            Saya memperakui bahawa maklumat yang diberikan di dalam penyata ini adalah benar, lengkap dan betul mengikut Seksyen 83(1A) Akta Cukai Pendapatan 1967.
            <div class="grid-2" style="margin-top: 14px; margin-bottom: 0;">
                <div>
                    <div><strong>Nama Pegawai:</strong> {{ $company->contact_person ?? 'Ahmad Tajudin' }}</div>
                    <div><strong>Jawatan:</strong> Pengarah / Ketua Pegawai Eksekutif</div>
                </div>
                <div style="text-align: right;">
                    <div><strong>Tarikh Dikeluarkan:</strong> {{ date('d F Y') }}</div>
                    <div><strong>Alamat:</strong> {{ $company->address ?? 'Kuala Lumpur, Malaysia' }}</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
