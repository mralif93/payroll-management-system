<?php

use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ExportAndBankingController;
use App\Http\Controllers\Admin\PayrollRunController;
use App\Http\Controllers\Admin\StatutoryParameterController;
use App\Http\Controllers\Admin\TaxFormEaController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Public Landing Page (Zero CSS, High Conversion, Calculator Demo)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Component Kit Playground for Development & Design QA
Route::get('/demo', function () {
    return view('demo');
})->name('demo');

// Guest-Only Authentication Routes (Auto-redirect authenticated admins to /admin)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Protected Admin Console Portal (Requires Authentication)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Operations
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Employee Directory & Profiles
    Route::resource('employees', EmployeeController::class)->only(['index', 'store', 'show', 'update']);

    // 3. Monthly Payroll Runs
    Route::get('/payroll', [PayrollRunController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollRunController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollRun}', [PayrollRunController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payrollRun}/approve', [PayrollRunController::class, 'approve'])->name('payroll.approve');

    // 4. Banking Autopay & Statutory Exporters
    Route::get('/banking', [ExportAndBankingController::class, 'index'])->name('banking.index');
    Route::post('/banking/{payrollRun}/bank-file', [ExportAndBankingController::class, 'generateBankFile'])->name('banking.bank-file');
    Route::post('/banking/{payrollRun}/statutory-file', [ExportAndBankingController::class, 'generateStatutoryFile'])->name('banking.statutory-file');

    // 5. Year-End Tax Form EA
    Route::get('/tax-ea', [TaxFormEaController::class, 'index'])->name('tax-ea.index');
    Route::post('/tax-ea/compile', [TaxFormEaController::class, 'compileAnnual'])->name('tax-ea.compile');

    // 6. Statutory Parameters Engine
    Route::get('/parameters', [StatutoryParameterController::class, 'index'])->name('parameters');
    Route::post('/parameters', [StatutoryParameterController::class, 'store'])->name('parameters.store');

    // 7. Audit Trails & Governance
    Route::get('/audit', [AuditTrailController::class, 'index'])->name('audit');
});

// Authentication Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
