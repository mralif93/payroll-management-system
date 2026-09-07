# PayFlow MY — Enterprise Malaysian Payroll Management System

<p align="center">
  <a href="https://mralif93.github.io/payroll-management-system/">
    <img src="https://img.shields.io/badge/Live_Showcase-GitHub_Pages-6366f1?style=for-the-badge&logo=github&logoColor=white" alt="Live Showcase on GitHub Pages">
  </a>
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/RBAC-Spatie-blue?style=for-the-badge&logo=shield" alt="RBAC">
  <img src="https://img.shields.io/badge/Malaysian_Statutory-2026_Compliant-emerald?style=for-the-badge&logo=checkmarx" alt="Statutory 2026">
</p>

---

## 🌐 Live Landing Page & Interactive Showcase
Experience the live, interactive demo and operational specifications deployed on GitHub Pages:  
👉 **[https://mralif93.github.io/payroll-management-system/](https://mralif93.github.io/payroll-management-system/)**

Includes interactive client-side sandboxes for:
- 🧮 **Real-Time Statutory Deduction Simulator** (KWSP/EPF, PERKESO Act 4, June 2026 SKBBK *Lindung 24 Jam*, SIP/EIS, and LHDN PCB)
- 📋 **Employment Contract Classification Matrix** (Local contract, expatriate/foreign contract, freelance, and intern schemes)
- 🏦 **1-Click Bank Autopay Formatter** (Maybank2e HDR/DTL structured text & CIMB BizChannel Bulk CSV)
- 🏛️ **Government Statutory Exporters** (EPF i-Akaun, PERKESO ASSIST, and LHDN CP39 text generator)
- 🎨 **13-Part Global UI Component Kit Reference** (Buttons, modals, badges, inputs, peer toggles, and dark mode controls)

---

## 📋 System Overview & Architecture

**PayFlow MY** is an enterprise-grade Malaysian Payroll Management System built on Laravel 11.x and Tailwind CSS v4. It automates employee compensation, statutory deductions, bank autopay batch disbursements, government filings, and year-end tax Form EA generation in compliance with Malaysian labor and tax legislation, fully integrated with the **CentraFlow Central Identity & SSO Hub**.

```text
+-----------------------------------------------------------------------------------+
|                            CentraFlow SSO Hub (:8004)                             |
|              OAuth 2.0 Authorization Server / Master User Registry                |
+-----------------------------------------+-----------------------------------------+
                                          | OAuth 2.0 Auth Code Grant (Port :8002)
                                          v
+-----------------------------------------------------------------------------------+
|                            PayFlow MY Engine (:8002)                              |
|                                                                                   |
|  [Staff & Statutory Profiles]  <--->  [Monthly Payroll Runs]  <--->  [Tax Form EA] |
|               |                                |                             |    |
|               +--------------------------------+-----------------------------+    |
|                                                |                                  |
|                                                v                                  |
|                               [Disbursement & Export Feeds]                       |
|                                (Maybank2e / CIMB / EPF / SOCSO / PCB)             |
+------------------------------------------------+----------------------------------+
                                                 ^
                                                 | Sync Feeder Data
                    +----------------------------+--------+
                    |       PulseHR Suite (:8001)         |
                    | Attendance, Overtime & Unpaid Leave |
                    +-------------------------------------+
```

> [!NOTE]
> **Decoupled Architecture & Federation**:
> 1. **Central Identity Provider**: User authentication, role provisioning, and session governance are managed via **[CentraFlow](http://localhost:8004)** using OAuth 2.0 Authorization Code Grant (`payroll:run`, `payroll:read`). Local password logins are decommissioned in favor of SSO.
> 2. **Decoupled HR Feeder Synchronization**: Feeder data (working days, overtime hours, and unpaid leave proration) is consumed from **[PulseHR (HRMS)](https://github.com/mralif93/human-resources-management-system)**, decoupling workforce governance from financial and statutory computation.

---

## 📌 Statutory Regulatory Compliance Matrix

| Regulatory Body | Legislative Act | System Implementation & Engine Rules | Status |
| :--- | :--- | :--- | :---: |
| **KWSP / EPF** | Employees Provident Fund Act 1991 | Standard 11% EE, 13% ER (wages $\le \text{RM}5,000$), 12% ER (wages $> \text{RM}5,000$), Senior 60+ (0%/4%), Foreign worker baseline (2%), and voluntary 9% toggle. | `Compliant` |
| **PERKESO / SOCSO** | Employees' Social Security Act 1969 | Category 1 (Employment Injury & Invalidity) & Category 2 with dynamic monthly wage ceiling cap ($\text{RM}6,000$). | `Compliant` |
| **PERKESO / SKBBK** | *LINDUNG 24 JAM* (Effective June 2026) | 24-hour Non-Employment Injury Scheme tier table with employee-funded contributions. | `Compliant` |
| **PERKESO / EIS** | Employment Insurance System Act 2017 | 0.2% Employee + 0.2% Employer contribution matching the $\text{RM}6,000$ wage ceiling. | `Compliant` |
| **LHDN / HASiL** | Income Tax Act 1967 | Official computerized calculation method for PCB/MTD, Form TP1/TP3 reliefs, and CP39 exports. | `Compliant` |
| **JTK / KSM** | Employment Act 1955 (Amended 2022/2023) | Ordinary Rate of Pay (ORP: $\frac{\text{Basic}}{26}$), 7-day payment window, 50% max deduction threshold, and 1.5x/2.0x/3.0x OT multipliers. | `Compliant` |
| **HRD Corp** | Pembangunan Sumber Manusia Berhad Act 2001 | 1.0% mandatory employer levy for qualifying enterprises (0.5% optional). | `Compliant` |
| **PDPA 2010** | Personal Data Protection Act 2010 | Field-level encryption for sensitive PII (NRIC, Passport, Bank Account Numbers) and frozen immutable state locks. | `Compliant` |

---

## 🧩 Core Payroll Modules

| # | Module | Key Features & Capabilities |
|---|---|---|
| **01** | **Authentication & Central SSO** | Enterprise SSO-only login powered by **CentraFlow** (OAuth 2.0 Authorization Code Grant), automated role syncing, and login audit trails. |
| **02** | **Staff & Statutory Profiles** | Master directory with NRIC encryption, citizenship categories, marital status, and tax relief profiles. |
| **03** | **Monthly Payroll Runs** | Automated computation of gross pay, statutory cuts (EPF, SOCSO, SKBBK, EIS, PCB), net salary, and approval lock workflow. |
| **04** | **Leave & Entitlements** | Tracking annual/medical balances, leave application workflows, and unpaid leave salary deductions. |
| **05** | **Bank Autopay Exports** | 1-click export of structured batch files for **Maybank2e (HDR/DTL)** and **CIMB BizChannel (CSV)**. |
| **06** | **Statutory Filings & Tax** | Automated file generation for **EPF i-Akaun (.txt)**, **SOCSO ASSIST (.csv)**, **LHDN CP39 (.txt)**, and digital **Form EA (C.P.8A)**. |
| **07** | **System Governance & Audit** | Gazetted statutory parameter tables, multi-department cost center assignment, and immutable audit logs. |

---

## 🔐 Role-Based Access Control (RBAC) Matrix

| Module / Area | Super Administrator | Payroll Officer | Finance Director | Internal Auditor |
| :--- | :---: | :---: | :---: | :---: |
| **System Governance & Audit Trails** | Full Access | No Access | No Access | Read-Only |
| **Staff Directory & Profiles** | Full Access | Full Access | View Only | View Only |
| **Statutory Rates & Allowances** | Full Access | View Only | View Only | View Only |
| **Payroll Run Creation & Calculate** | Full Access | Full Access | View Only | View Only |
| **Payroll Approval & Run Lock** | Full Access | No Access | Full Access | No Access |
| **Bank Autopay Batch Generation** | Full Access | Full Access | Full Access | View Only |
| **Statutory Exports (EPF, SOCSO, PCB)**| Full Access | Full Access | Full Access | View Only |
| **Year-End Form EA Compilation** | Full Access | Full Access | Full Access | View Only |

---

## 🚀 Quick Start (Local Setup)

```bash
# 1. Clone repository
git clone https://github.com/mralif93/payroll-management-system.git
cd payroll-management-system

# 2. Install PHP & Node dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# CentraFlow SSO Configuration (in .env)
# CENTRAFLOW_HOST=http://localhost:8004
# CENTRAFLOW_CLIENT_ID=9d12a101-0002-4000-8000-000000000002
# CENTRAFLOW_CLIENT_SECRET=payroll_secret_centraflow_2026
# CENTRAFLOW_REDIRECT_URI=http://localhost:8002/auth/callback
# CENTRAFLOW_SCOPES="payroll:run payroll:read"

# 4. Database migrations & seeders
php artisan migrate --seed

# 5. Build frontend assets & run dev server
npm run build
php artisan serve --port=8002
```

Authentication is centrally governed via **CentraFlow SSO** (`http://localhost:8004`). Pre-registered demo credentials on CentraFlow:
- **HR & Payroll Manager:** `hr.manager@centraflow.local` / `password` (maps to Payroll Officer)
- **Super Administrator:** `superadmin@centraflow.local` / `password` (maps to Super Administrator)

---

## 📄 Documentation & Specifications
- 📘 [Software Requirements Specification (SRS)](documentation/software-requirement-specifiction.md)
- 🔐 [Sub-System Authentication Integration Manual (SSO with CentraFlow)](documentation/subsystem-auth-guide.md)
- 🌐 [GitHub Pages Landing Page Showcase](https://mralif93.github.io/payroll-management-system/)
- 🔗 [External Decoupled Human Resources Management System (PulseHR)](https://github.com/mralif93/human-resources-management-system)

---

## 📄 License

This project is licensed under the MIT License.
