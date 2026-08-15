# Software Requirements Specification (SRS) & Development Blueprint
## Malaysian Payroll Management System (Laravel 11.x Architecture)

---

## 1. Executive Summary & Regulatory Framework

### 1.1 Scope & Purpose
This document provides complete technical specifications, database migrations, logic services, export drivers, and architectural guidelines for building an enterprise-grade, web-based Malaysian Payroll Management System using the Laravel Framework.

The system automates employee compensation, statutory deductions, monthly bank autopay exports, government text/CSV submissions, annual tax documentation (Form EA/E), and audit trails while guaranteeing full compliance with Malaysian statutory acts and regulations.

### 1.2 Malaysian Statutory Regulatory Compliance Map

| Regulatory Body / Act | Legislative Reference | System Compliance Requirements |
| :--- | :--- | :--- |
| **JTK / KSM** | Employment Act 1955 (Amended 2022/2023) | Wage payment window (within 7 days of period end), max 45 working hours/week, $50\%$ max monthly wage deduction cap, and statutory Overtime (OT) multipliers ($1.5 \times$, $2.0 \times$, $3.0 \times$). |
| **KWSP / EPF** | Employees Provident Fund Act 1991 | Employee ($11\%$ standard, optional $9\%$) & Employer ($13\%$ for wages $\le \text{RM}5,000$, $12\%$ for wages $> \text{RM}5,000$). Age $60+$ rates ($0\%$ employee / $4\%$ employer). Foreign workers require a mandatory $2\%$ contribution rate each side. |
| **PERKESO / SOCSO** | Employees' Social Security Act 1969 | Employment Injury & Invalidity Scheme (Category 1) vs. Employment Injury Only (Category 2: age $60+$ or non-citizens). Statutory monthly wage ceiling capped at $\text{RM}6,000$. |
| **PERKESO / EIS** | Employment Insurance System Act 2017 | Job placement & retrenchment fund scheme ($0.2\%$ Employee / $0.2\%$ Employer) up to the $\text{RM}6,000$ wage ceiling. Excludes foreign workers and employees aged $60+$ at first entry. |
| **LHDN / HASiL** | Income Tax Act 1967 | Computerised Calculation Method for Monthly Tax Deductions (PCB/MTD), Form TP1/TP3 processing, CP39 text generation, annual Form EA PDF generation, and Form E/C.P.8D submission structures. |
| **HRD Corp** | Pembangunan Sumber Manusia Berhad Act 2001 | Mandatory $1.0\%$ levy for employers with $10+$ Malaysian employees (optional $0.5\%$ for 5–9 employees) based on gross monthly remuneration. |
| **PDPA** | Personal Data Protection Act 2010 | Mandatory field-level encryption for sensitive Personally Identifiable Information (NRIC, Passports, Bank Accounts) and granular role-based access logs. |

---

## 2. Technology Stack & Directory Architecture

### 2.1 Recommended Technology Stack
- **Framework:** Laravel 11.x (PHP 8.3+)
- **Database Engine:** PostgreSQL 16+ (recommended for strict decimal precision & native JSON) or MySQL 8.0+
- **Frontend Layer:** Laravel Livewire v3 (with Alpine.js & Tailwind CSS) OR Inertia.js with Vue 3 / React
- **Queue Engine:** Redis 7.x + Laravel Horizon (for batch processing payroll runs, PDF compilation, and export generation)
- **Document Engine:** `barryvdh/laravel-dompdf` or `spatie/browsershot` (for Payslip & Form EA compilation)
- **Security & Auditing:** Native Laravel Encryption (`CastsInboundAttributes`), `spatie/laravel-permission` (RBAC), and `spatie/laravel-activitylog`

### 2.2 Domain Directory Structure Architecture

```
app/
├── Enums/
│   ├── MaritalStatus.php
│   ├── PayrollStatus.php
│   └── TaxCategory.php
├── Http/
│   ├── Controllers/
│   │   ├── BankExportController.php
│   │   ├── PayrollProcessingController.php
│   │   └── StatutoryExportController.php
│   └── Livewire/
│       ├── EmployeeManager.php
│       └── PayrollRunProcessor.php
├── Models/
│   ├── Employee.php
│   ├── PayrollItem.php
│   ├── PayrollRun.php
│   ├── SalaryComponent.php
│   └── StatutoryParameter.php
└── Services/
    ├── Exporters/
    │   ├── BankExporterFactory.php
    │   ├── CimbBizChannelExporter.php
    │   ├── EPFFileExporter.php
    │   ├── LhdnCp39Exporter.php
    │   ├── Maybank2eExporter.php
    │   └── SocsoFileExporter.php
    └── Payroll/
        ├── EPFCalculatorService.php
        ├── HrdCorpCalculatorService.php
        ├── LhdnPcbCalculatorService.php
        ├── OvertimeAndProrationService.php
        ├── SOCSOCalculatorService.php
        └── StatutoryParameterResolver.php
```

---

## 3. Database Schema Blueprint (Laravel Migrations)

### 3.1 Employees Statutory Master Table (`employees`)

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id')->unique();
            $table->string('full_name');
            $table->text('ic_or_passport'); // Encrypted: NRIC or Passport
            $table->boolean('is_malaysian')->default(true);
            $table->boolean('is_tax_resident')->default(true);
            $table->date('date_of_birth');
            $table->enum('marital_status', [
                'single', 
                'married_working_spouse', 
                'married_non_working_spouse', 
                'divorced_widowed'
            ])->default('single');
            $table->integer('number_of_children')->default(0);

            // Statutory Identifiers
            $table->string('epf_number')->nullable();
            $table->string('socso_number')->nullable();
            $table->string('income_tax_number')->nullable();
            $table->enum('tax_category', ['category_1', 'category_2', 'category_3'])->default('category_1');

            // Statutory Overrides & Status Flags
            $table->boolean('epf_employee_rate_override')->default(false);
            $table->decimal('epf_employee_custom_rate', 5, 4)->nullable(); // e.g. 0.0900 for 9%
            $table->boolean('is_disabled')->default(false);
            $table->boolean('spouse_is_disabled')->default(false);

            // Payment / Banking
            $table->string('bank_code')->nullable(); // e.g., 'MAYBANK', 'CIMB'
            $table->text('bank_account_number')->nullable(); // Encrypted
            $table->date('joined_date');
            $table->date('resigned_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('employees');
    }
};
```

### 3.2 Dynamic Statutory Parameters Table (`statutory_parameters`)

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('statutory_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g. 'socso_ceiling', 'epf_rates', 'minimum_wage'
            $table->string('parameter_key'); // e.g. 'max_wage_limit', 'employer_rate_under_5k'
            $table->decimal('numeric_value', 12, 4)->nullable();
            $table->json('json_value')->nullable(); // Holds dynamic brackets or tax matrices
            $table->date('effective_from');
            $table->date('effective_to')->nullable(); // NULL indicates active indefinitely
            $table->timestamps();

            $table->index(['category', 'effective_from', 'effective_to']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('statutory_parameters');
    }
};
```

### 3.3 Salary Components Table (`salary_components`)

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic Pay, Transport Allowance, Bonus, Unpaid Leave
            $table->enum('type', ['earning', 'deduction']);
            
            // Statutory Taxability Flags (Malaysia)
            $table->boolean('is_epf_subject')->default(true);
            $table->boolean('is_socso_subject')->default(true);
            $table->boolean('is_eis_subject')->default(true);
            $table->boolean('is_pcb_subject')->default(true);
            $table->boolean('is_hrd_subject')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('salary_components');
    }
};
```

### 3.4 Payroll Runs & Payslip Items (`payroll_runs` & `payroll_items`)

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('period_year', 4); // "2026"
            $table->string('period_month', 2); // "08"
            $table->date('cutoff_date');
            $table->date('payment_date');
            $table->enum('status', ['draft', 'approved', 'paid', 'locked'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained();

            // Salary Computations
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('gross_salary', 12, 2);
            $table->decimal('overtime_amount', 12, 2)->default(0.00);
            $table->decimal('allowances_total', 12, 2)->default(0.00);
            $table->decimal('unpaid_leave_deduction', 12, 2)->default(0.00);

            // Subject Wage Computations
            $table->decimal('epf_subject_wages', 12, 2);
            $table->decimal('socso_subject_wages', 12, 2);
            $table->decimal('pcb_subject_wages', 12, 2);

            // Employee Statutory Deductions
            $table->decimal('epf_employee', 12, 2);
            $table->decimal('socso_employee', 12, 2);
            $table->decimal('eis_employee', 12, 2);
            $table->decimal('pcb_amount', 12, 2);
            $table->decimal('zakat_amount', 12, 2)->default(0.00);

            // Employer Statutory Contributions
            $table->decimal('epf_employer', 12, 2);
            $table->decimal('socso_employer', 12, 2);
            $table->decimal('eis_employer', 12, 2);
            $table->decimal('hrd_levy_amount', 12, 2);

            // Final Net Pay
            $table->decimal('net_salary', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
    }
};
```

---

## 4. Eloquent Models & Data Protection (PDPA Compliance)

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id', 'full_name', 'ic_or_passport', 'is_malaysian', 'is_tax_resident',
        'date_of_birth', 'marital_status', 'number_of_children', 'epf_number',
        'socso_number', 'income_tax_number', 'tax_category', 'epf_employee_rate_override',
        'epf_employee_custom_rate', 'is_disabled', 'spouse_is_disabled', 'bank_code',
        'bank_account_number', 'joined_date', 'resigned_date'
    ];

    /**
     * Encrypt sensitive PII fields automatically for PDPA compliance.
     */
    protected $casts = [
        'ic_or_passport'      => 'encrypted',
        'bank_account_number' => 'encrypted',
        'is_malaysian'        => 'boolean',
        'is_tax_resident'     => 'boolean',
        'is_disabled'         => 'boolean',
        'spouse_is_disabled'  => 'boolean',
        'date_of_birth'       => 'date',
        'joined_date'         => 'date',
        'resigned_date'       => 'date',
    ];
}
```

---

## 5. Effective-Dated Statutory Resolver Engine

```php
namespace App\Services\Payroll;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class StatutoryParameterResolver
{
    /**
     * Fetch parameter value matching the specific payroll cut-off date.
     */
    public function getParameter(string $category, string $key, string $payrollDate): float
    {
        $targetDate = Carbon::parse($payrollDate)->toDateString();

        $parameter = DB::table('statutory_parameters')
            ->where('category', $category)
            ->where('parameter_key', $key)
            ->where('effective_from', '<=', $targetDate)
            ->where(function ($query) use ($targetDate) {
                $query->whereNull('effective_to')
                      ->orWhere('effective_to', '>=', $targetDate);
            })
            ->orderBy('effective_from', 'desc')
            ->first();

        if (!$parameter) {
            throw new Exception("Statutory parameter [{$category}.{$key}] not active for date: {$targetDate}");
        }

        return (float) $parameter->numeric_value;
    }
}
```

---

## 6. Statutory Calculation Engines (Logic & Services)

### 6.1 EPF Calculation Engine (`EPFCalculatorService`)

```php
namespace App\Services\Payroll;

class EPFCalculatorService
{
    public function calculate(
        float $subjectWages, 
        bool $isMalaysian, 
        int $age, 
        ?float $customEmployeeRate = null
    ): array {
        if ($subjectWages <= 0) {
            return ['employee' => 0.00, 'employer' => 0.00];
        }

        // Non-Malaysian Citizens: Mandatory statutory baseline rate (2% each side)
        if (!$isMalaysian) {
            return [
                'employee' => round($subjectWages * 0.02, 2),
                'employer' => round($subjectWages * 0.02, 2),
            ];
        }

        // Malaysian Citizens Age 60 and above
        if ($age >= 60) {
            return [
                'employee' => 0.00,
                'employer' => (float) ceil($subjectWages * 0.04), // 4% employer contribution
            ];
        }

        // Standard Rules (Age < 60)
        $employeeRate = $customEmployeeRate ?? 0.11; // Standard 11% (or optional 9%)
        $employerRate = ($subjectWages <= 5000.00) ? 0.13 : 0.12; // 13% for <=RM5k, 12% for >RM5k

        return [
            'employee' => (float) ceil($subjectWages * $employeeRate), // EPF rounds up to nearest Ringgit
            'employer' => (float) ceil($subjectWages * $employerRate),
        ];
    }
}
```

### 6.2 SOCSO, SKBBK & EIS Calculation Engine (`SOCSOCalculatorService`)

Effective **1 June 2026**, Malaysia PERKESO implemented the **Non-Employment Injury Scheme (SKBBK / *LINDUNG 24 JAM*)** providing 24-hour accident coverage funded by employee contributions alongside standard Act 4 and SIP EIS Act 800 tables.

```php
namespace App\Services\Payroll;

class SOCSOCalculatorService
{
    protected StatutoryParameterResolver $parameterResolver;

    public function __construct(StatutoryParameterResolver $parameterResolver)
    {
        $this->parameterResolver = $parameterResolver;
    }

    public function calculate(float $subjectWages, int $age, bool $isMalaysian, string $payrollDate): array
    {
        // Resolve dynamic wage ceiling for SOCSO/EIS (RM6,000 ceiling limit)
        $wageCeiling = $this->parameterResolver->getParameter('socso_ceiling', 'wage_ceiling', $payrollDate);
        $cappedWages = min($subjectWages, $wageCeiling);
        $isJune2026OrLater = Carbon::parse($payrollDate)->greaterThanOrEqualTo('2026-06-01');

        // Foreign workers or workers aged >= 60 use Category 2 (Employment Injury Only)
        if ($age >= 60 || !$isMalaysian) {
            return [
                'socso_employee' => 0.00,
                'skbbk_employee' => ($isJune2026OrLater && $age >= 60 && $isMalaysian) ? 7.00 : 0.00,
                'socso_employer' => round($cappedWages * 0.0125, 2), // 1.25%
                'eis_employee'   => 0.00,
                'eis_employer'   => 0.00,
            ];
        }

        // Category 1: Full Employment Injury & Invalidity Scheme + SKBBK + EIS
        $baseSocsoEe = round($cappedWages * 0.005, 2);   // 0.5% Act 4 Base
        $skbbkEe = $isJune2026OrLater ? round($cappedWages * 0.00725, 2) : 0.00; // SKBBK Lindung 24 Jam

        return [
            'socso_employee' => $baseSocsoEe,
            'skbbk_employee' => $skbbkEe,
            'socso_employer' => round($cappedWages * 0.0175, 2),  // 1.75%
            'eis_employee'   => round($cappedWages * 0.002, 2),   // 0.2%
            'eis_employer'   => round($cappedWages * 0.002, 2),   // 0.2%
        ];
    }
}
```

### 6.3 LHDN PCB Computerised Calculation Engine (`LhdnPcbCalculatorService`)

#### Official LHDN PCB Computerised Formula
$$\text{Net PCB for Current Month} = \frac{\left[ (P - M) \times R + B \right] - (Z + X)}{n + 1}$$

Where:
- $P$: Projected annual net chargeable income.
- $M$: Lower boundary of annual tax bracket.
- $R$: Progressive tax rate.
- $B$: Base tax payable for bracket.
- $Z$: Accumulated prior zakat payments.
- $X$: Accumulated prior PCB deductions.
- $n + 1$: Remaining months in year including current month.

```php
namespace App\Services\Payroll;

use App\Models\Employee;
use App\Models\PayrollItem;
use Carbon\Carbon;

class LhdnPcbCalculatorService
{
    protected array $taxBrackets = [
        ['min' => 0,        'max' => 5000,     'base_tax' => 0,       'rate' => 0.00],
        ['min' => 5001,     'max' => 20000,    'base_tax' => 0,       'rate' => 0.01],
        ['min' => 20001,    'max' => 35000,    'base_tax' => 150,     'rate' => 0.03],
        ['min' => 35001,    'max' => 50000,    'base_tax' => 600,     'rate' => 0.06],
        ['min' => 50001,    'max' => 70000,    'base_tax' => 1500,    'rate' => 0.11],
        ['min' => 70001,    'max' => 100000,   'base_tax' => 3700,    'rate' => 0.19],
        ['min' => 100001,   'max' => 400000,   'base_tax' => 9400,    'rate' => 0.25],
        ['min' => 400001,   'max' => 600000,   'base_tax' => 84400,   'rate' => 0.26],
        ['min' => 600001,   'max' => 2000000,  'base_tax' => 136400,  'rate' => 0.28],
        ['min' => 2000001,  'max' => PHP_INT_MAX, 'base_tax' => 528400, 'rate' => 0.30],
    ];

    public function calculate(
        Employee $employee,
        float $currentRemuneration,
        float $currentEpf,
        string $payrollDate,
        float $additionalRemuneration = 0.00,
        float $additionalEpf = 0.00,
        float $tp1Reliefs = 0.00,
        float $currentZakat = 0.00
    ): float {
        // Flat 30% tax rule for non-tax residents
        if (!$employee->is_tax_resident) {
            return round(($currentRemuneration + $additionalRemuneration) * 0.30, 2);
        }

        $date = Carbon::parse($payrollDate);
        $currentMonth = $date->month;
        $remainingMonthsIncludingCurrent = 12 - $currentMonth + 1; // (n + 1)

        $ytd = $this->getYtdAccumulatedData($employee, $date->year, $currentMonth);

        // EPF deduction cap for PCB relief (RM4,000 annual max ~ RM333.33/month)
        $netCurrentNormal = max(0, $currentRemuneration - min($currentEpf, 333.33));
        $netYtdNormal = $ytd['accumulated_net_remuneration'];

        // Projected Annual Gross Chargeable Income
        $projectedAnnualRemuneration = $netYtdNormal + $netCurrentNormal + ($netCurrentNormal * ($remainingMonthsIncludingCurrent - 1));

        // Allowable Reliefs (D, S, DU, SU, QC, TP1)
        $totalReliefs = $this->calculateTotalReliefs($employee, $tp1Reliefs);
        $P = max(0, $projectedAnnualRemuneration - $totalReliefs);

        // Annual Base Tax Payable
        $annualTaxPayable = $this->calculateAnnualTaxFromBrackets($P);

        // Prior Tax Deductions & Zakat Adjustments
        $totalPriorPcb = $ytd['accumulated_pcb'];
        $totalZakat = $ytd['accumulated_zakat'] + $currentZakat;

        $remainingTax = max(0, $annualTaxPayable - $totalZakat - $totalPriorPcb);
        $monthlyPcb = $remainingTax / $remainingMonthsIncludingCurrent;

        // Additional Remuneration (Bonus/Commission) Tax Delta Adjustment
        if ($additionalRemuneration > 0) {
            $monthlyPcb += $this->calculateAdditionalRemunerationPcb(
                $P,
                $additionalRemuneration - $additionalEpf,
                $annualTaxPayable
            );
        }

        return round(max(0, $monthlyPcb), 2);
    }

    protected function calculateTotalReliefs(Employee $employee, float $tp1Reliefs): float
    {
        $reliefs = 9000.00; // Individual Relief (D)

        if ($employee->marital_status === 'married_non_working_spouse') {
            $reliefs += 4000.00; // Spouse Relief (S)
        }
        if ($employee->is_disabled) {
            $reliefs += 6000.00; // Disabled Individual (DU)
        }
        if ($employee->marital_status === 'married_non_working_spouse' && $employee->spouse_is_disabled) {
            $reliefs += 5000.00; // Disabled Spouse (SU)
        }

        $reliefs += ($employee->number_of_children * 2000.00); // Child Relief (QC)
        $reliefs += $tp1Reliefs; // TP1 Declared Reliefs

        return $reliefs;
    }

    protected function calculateAnnualTaxFromBrackets(float $P): float
    {
        foreach ($this->taxBrackets as $bracket) {
            if ($P >= $bracket['min'] && $P <= $bracket['max']) {
                $excess = $P - ($bracket['min'] - 1);
                return $bracket['base_tax'] + ($excess * $bracket['rate']);
            }
        }
        return 0.00;
    }

    protected function calculateAdditionalRemunerationPcb(float $baseP, float $netAdditional, float $baseAnnualTax): float
    {
        $newP = $baseP + $netAdditional;
        $newAnnualTax = $this->calculateAnnualTaxFromBrackets($newP);
        return max(0, $newAnnualTax - $baseAnnualTax);
    }

    protected function getYtdAccumulatedData(Employee $employee, int $year, int $currentMonth): array
    {
        $payrollData = PayrollItem::whereHas('payrollRun', function ($q) use ($year, $currentMonth) {
                $q->where('period_year', (string)$year)
                  ->where('period_month', '<', sprintf('%02d', $currentMonth))
                  ->where('status', 'locked');
            })
            ->where('employee_id', $employee->id)
            ->selectRaw('
                SUM(gross_salary - epf_employee) as net_remuneration,
                SUM(pcb_amount) as total_pcb,
                SUM(zakat_amount) as total_zakat
            ')
            ->first();

        return [
            'accumulated_net_remuneration' => (float) ($payrollData->net_remuneration ?? 0),
            'accumulated_pcb'              => (float) ($payrollData->total_pcb ?? 0),
            'accumulated_zakat'            => (float) ($payrollData->total_zakat ?? 0),
        ];
    }
}
```

### 6.4 HRD Corp & Overtime Engine Services

```php
namespace App\Services\Payroll;

class HrdCorpCalculatorService
{
    public function calculate(float $grossSalary, int $totalMalaysianStaffCount): float
    {
        if ($totalMalaysianStaffCount >= 10) {
            return round($grossSalary * 0.01, 2); // Mandatory 1.0%
        } elseif ($totalMalaysianStaffCount >= 5) {
            return round($grossSalary * 0.005, 2); // Optional 0.5%
        }

        return 0.00;
    }
}

class OvertimeAndProrationService
{
    /**
     * Employment Act 1955 Section 60I Overtime Computations
     */
    public function calculateOT(float $basicSalary, float $hoursWorked, string $otType, int $dailyStandardHours = 8): float
    {
        $orp = $basicSalary / 26; // Ordinary Rate of Pay
        $hrp = $orp / $dailyStandardHours; // Hourly Rate of Pay

        $multiplier = match ($otType) {
            'normal_day'     => 1.5,
            'rest_day'       => 2.0,
            'public_holiday' => 3.0,
            default          => 1.5,
        };

        return round($hoursWorked * $hrp * $multiplier, 2);
    }

    /**
     * Unpaid Leave Deduction (NPL)
     */
    public function calculateUnpaidLeaveDeduction(float $basicSalary, int $unpaidDays, int $daysInMonth = 26): float
    {
        return round(($basicSalary / $daysInMonth) * $unpaidDays, 2);
    }
}
```

---

## 7. Statutory Export Drivers & Tax Form Generators

### 7.1 EPF i-Akaun & PERKESO ASSIST Exporters

```php
namespace App\Services\Exporters;

use Illuminate\Support\Collection;

class EPFFileExporter
{
    public function generate(string $employerEpfNo, Collection $payrollItems): string
    {
        $lines = [];
        foreach ($payrollItems as $item) {
            $lines[] = implode('|', [
                str_pad($employerEpfNo, 10, '0', STR_PAD_LEFT),
                str_replace('-', '', $item->employee->ic_or_passport),
                substr(strtoupper($item->employee->full_name), 0, 40),
                str_pad($item->employee->epf_number ?? '', 12, ' ', STR_PAD_RIGHT),
                number_format($item->epf_employer, 2, '.', ''),
                number_format($item->epf_employee, 2, '.', ''),
            ]);
        }
        return implode("\r\n", $lines);
    }
}

class SocsoFileExporter
{
    public function generate(string $employerSocsoNo, Collection $payrollItems): string
    {
        $lines = [];
        foreach ($payrollItems as $item) {
            $lines[] = implode(',', [
                $employerSocsoNo,
                str_replace('-', '', $item->employee->ic_or_passport),
                '"' . strtoupper($item->employee->full_name) . '"',
                number_format($item->socso_employer, 2, '.', ''),
                number_format($item->socso_employee, 2, '.', ''),
                number_format($item->eis_employer, 2, '.', ''),
                number_format($item->eis_employee, 2, '.', ''),
            ]);
        }
        return implode("\r\n", $lines);
    }
}
```

### 7.2 LHDN CP39 Exporter & Form EA Data Payload

```php
namespace App\Services\Exporters;

use App\Models\Employee;
use App\Models\PayrollItem;
use Illuminate\Support\Collection;

class LhdnCp39Exporter
{
    public function generate(string $employerTaxNo, Collection $payrollItems): string
    {
        $lines = [];
        foreach ($payrollItems as $item) {
            if ($item->pcb_amount <= 0) continue;

            $lines[] = sprintf(
                "%-10s%-12s%-60s%010d",
                $employerTaxNo,
                $item->employee->income_tax_number ?? '',
                str_pad(substr(strtoupper($item->employee->full_name), 0, 60), 60),
                (int) round($item->pcb_amount * 100) // Formatted as cents/sen without decimal
            );
        }
        return implode("\r\n", $lines);
    }
}

class FormEaGeneratorService
{
    public function buildEaDataPayload(Employee $employee, int $year): array
    {
        $items = PayrollItem::whereHas('payrollRun', function ($q) use ($year) {
            $q->where('period_year', (string)$year)->where('status', 'locked');
        })->where('employee_id', $employee->id)->get();

        return [
            'year'                 => $year,
            'employee_name'        => $employee->full_name,
            'ic_passport'          => $employee->ic_or_passport,
            'tax_no'               => $employee->income_tax_number,
            'epf_no'               => $employee->epf_number,
            'gross_salary'         => $items->sum('gross_salary'),
            'total_pcb'            => $items->sum('pcb_amount'),
            'total_zakat'          => $items->sum('zakat_amount'),
            'total_epf_employee'   => $items->sum('epf_employee'),
            'total_socso_employee' => $items->sum('socso_employee'),
        ];
    }
}
```

---

## 8. Bank Autopay Payment Export Drivers

```php
namespace App\Services\Exporters;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use Exception;

class Maybank2eExporter
{
    public function generate(string $corporateId, string $employerAccountNo, string $valueDate, Collection $payrollItems): string
    {
        $lines = [];
        $totalAmount = $payrollItems->sum('net_salary');

        // Header Record (HDR)
        $lines[] = implode('|', [
            'HDR',
            str_pad($corporateId, 10, ' ', STR_PAD_RIGHT),
            str_pad($employerAccountNo, 16, ' ', STR_PAD_RIGHT),
            Carbon::parse($valueDate)->format('Ymd'),
            str_pad((string) $payrollItems->count(), 6, '0', STR_PAD_LEFT),
            number_format($totalAmount, 2, '.', ''),
        ]);

        // Detail Payment Records (DTL)
        foreach ($payrollItems as $index => $item) {
            $lines[] = implode('|', [
                'DTL',
                str_pad((string)($index + 1), 6, '0', STR_PAD_LEFT),
                substr(strtoupper(preg_replace('/[^A-Za-z0-9 ]/', '', $item->employee->full_name)), 0, 40),
                str_replace(['-', ' '], '', $item->employee->bank_account_number),
                number_format($item->net_salary, 2, '.', ''),
                str_replace('-', '', $item->employee->ic_or_passport),
                $item->employee->is_malaysian ? 'NRIC' : 'PASSPORT',
                'SALARY ' . Carbon::parse($valueDate)->format('M Y'),
            ]);
        }

        return implode("\r\n", $lines);
    }
}

class CimbBizChannelExporter
{
    public function generate(string $paymentDate, Collection $payrollItems): string
    {
        $lines = [];
        $lines[] = 'Record Type,Beneficiary Name,Beneficiary Account No,Amount,Beneficiary ID,Payment Description';

        foreach ($payrollItems as $item) {
            $lines[] = implode(',', [
                'CP',
                '"' . str_replace('"', '""', strtoupper($item->employee->full_name)) . '"',
                '"' . str_replace(['-', ' '], '', $item->employee->bank_account_number) . '"',
                number_format($item->net_salary, 2, '.', ''),
                '"' . str_replace('-', '', $item->employee->ic_or_passport) . '"',
                '"SALARY ' . Carbon::parse($paymentDate)->format('m/Y') . '"',
            ]);
        }
        return implode("\r\n", $lines);
    }
}

class BankExporterFactory
{
    public static function export(string $bankCode, array $config, Collection $payrollItems): string
    {
        return match (strtoupper($bankCode)) {
            'MAYBANK', 'MAYBANK2E' => (new Maybank2eExporter())->generate(
                $config['corporate_id'],
                $config['account_number'],
                $config['value_date'],
                $payrollItems
            ),
            'CIMB', 'CIMB_BIZCHANNEL' => (new CimbBizChannelExporter())->generate(
                $config['value_date'],
                $payrollItems
            ),
            default => throw new Exception("Unsupported bank payment driver: {$bankCode}"),
        };
    }
}
```

---

## 9. Security Controls & Statutory Audit Trails

### 9.1 Data Integrity & Audit Locking Rules
- **State Machine Locking:** When a `payroll_runs` record switches status to `locked`, an Eloquent Model Observer blocks updates/deletion of associated `payroll_items`.
- **Parameter Snapshots:** Statutory rates calculated during execution are saved permanently inside `payroll_items` to protect historical payslip integrity against future statutory rule revisions.
- **7-Year Retention Compliance:** Per LHDN statutory auditing regulations, database tables, payslips, and statutory exports must be archived for a minimum of 7 years.