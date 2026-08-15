# Malaysian Payroll Management System

An enterprise-grade, web-based Malaysian Payroll Management System built on Laravel 11.x, designed to automate employee compensation, statutory deductions, monthly bank autopay processing, government statutory file exports, and annual tax filings in compliance with Malaysian labor and tax legislation.

---

## 📌 Overview & Statutory Compliance

This system strictly adheres to Malaysian statutory regulatory frameworks:

- **Employment Act 1955 (Amended 2022/2023):** Overtime (OT) rate multipliers ($1.5\times, 2.0\times, 3.0\times$), max 45-hour work weeks, 7-day payment window, and 50% max monthly deduction limits.
- **KWSP / EPF (Employees Provident Fund Act 1991):** Statutory employee & employer contribution rates (including $\le \text{RM}5,000$ vs. $> \text{RM}5,000$ rules, senior age $60+$ rates, and foreign worker rates).
- **PERKESO / SOCSO (Employees' Social Security Act 1969):** Employment Injury & Invalidity schemes (Category 1 & Category 2) with statutory monthly wage ceiling caps ($\text{RM}6,000$).
- **PERKESO / SKBBK (*LINDUNG 24 JAM* - Effective 1 June 2026):** Non-Employment Injury Scheme providing 24-hour accident protection funded via employee contributions across official tiered wage brackets.
- **PERKESO / EIS (Employment Insurance System Act 2017):** $0.2\%$ employee and employer contributions up to the $\text{RM}6,000$ wage ceiling.
- **LHDN / HASiL (Income Tax Act 1967):** Full computerized calculation method for Monthly Tax Deductions (PCB/MTD), Form TP1/TP3 reliefs, CP39 text export, and Form EA / Form E generation.
- **HRD Corp:** $1.0\%$ mandatory levy for qualifying employers ($0.5\%$ optional).
- **PDPA 2010:** Automated encryption for sensitive personally identifiable information (NRIC, Passport, Bank Account Numbers).

---

## 🚀 Technology Stack & UI Architecture

| Layer | Technology |
| :--- | :--- |
| **Framework** | Laravel 11.x (PHP 8.3+) |
| **Database** | PostgreSQL 16+ / MySQL 8.0+ / SQLite |
| **Frontend Styling** | Tailwind CSS v4 (Zero Custom CSS policy, dark/light mode, mobile responsive) |
| **Icons & Animation** | Boxicons (`boxicons`) & Animate.css (`animate.css`) |
| **Queue & Cache** | Redis 7.x + Laravel Horizon |
| **Document / PDF Engine** | `barryvdh/laravel-dompdf` / `spatie/browsershot` |
| **Security & Auditing** | Laravel Native Encryption, `spatie/laravel-permission`, `spatie/laravel-activitylog` |

---

## 🎨 Global UI Components & Master Layouts

The application implements a zero-custom-CSS, pure Tailwind CSS component architecture:

- **Master Public Layout (`<x-layouts.app>`):** Sticky header, animated Dark/Light sliding pill switch, collapsible mobile navigation menu, and footer.
- **Master Admin Console Layout (`<x-layouts.admin>`):** Responsive sidebar with mobile slide-over drawer, breadcrumbs, and live indicator widgets.
- **Interactive Statutory Deduction Simulator:** Live calculations for EPF, Act 4 SOCSO, June 2026 SKBBK (*Lindung 24 Jam*), EIS, and PCB across single/married categories and voluntary EPF rate adjustments.
- **13 Global Blade UI Components (`resources/views/components/`):**
  1. `<x-button>`: Primary, secondary, dark, danger, and ghost variants.
  2. `<x-badge>`: Status badges with optional pulsing indicators.
  3. `<x-card>`: Surface card with header and footer slots.
  4. `<x-stat-card>`: Metric KPI counters with trends and icons.
  5. `<x-alert>`: Contextual banner notices with dismiss actions.
  6. `<x-modal>`: Popup dialogs with backdrop blur and zoom transitions.
  7. `<x-input>`: Form controls with prefix/suffix addons and error states.
  8. `<x-toggle>`: Peer-based toggle switches with smooth left/right sliding animations (`sm`, `md`, `lg` sizes & 6 color themes).
  9. `<x-theme-toggle>`: Interactive dark/light sliding pill switch.
  10. UI Kit Showcase at `/demo` with live component sandbox.

---

## 🛠️ Key Features

- **Automated Statutory Calculation Engines:** Accurate real-time calculations for EPF, SOCSO (Act 4), SKBBK (June 2026), EIS, PCB (Computerised Formula), and HRD Corp.
- **Effective-Dated Statutory Parameters:** Dynamically configured wage ceilings and rates mapped to effective dates.
- **Bank Autopay File Generators:** Ready-to-upload payment files for **Maybank2e (HDR/DTL format)** and **CIMB BizChannel (CSV)**.
- **Statutory Submission Generators:** Text and CSV export formatters for **EPF i-Akaun**, **PERKESO ASSIST**, and **LHDN CP39**.
- **Tax & Payslip Documents:** Digital payslip generator and automated annual **Form EA** builder.
- **Data Protection & Audit Locking:** Field-level encryption for sensitive PII and frozen state audit trails on approved/locked payroll runs.

---

## 📖 Documentation

For detailed architecture, schema migrations, formulas, and service implementations, refer to the [Software Requirements Specification (SRS) & Development Blueprint](documentation/software-requirement-specifiction.md).

---

## ⚙️ Getting Started

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & NPM
- PostgreSQL / MySQL / SQLite

### Installation

```bash
# Clone the repository
git clone https://github.com/mralif93/payroll-management-system.git
cd payroll-management-system

# Install PHP dependencies
composer install

# Copy environment file and generate application key
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Install frontend dependencies and build
npm install
npm run build

# Start the local development server
php artisan serve
```

---

## 📄 License

This project is licensed under the MIT License.
