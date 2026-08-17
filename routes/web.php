<?php

use App\Http\Controllers\Admin\AuditTrailController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ExportAndBankingController;
use App\Http\Controllers\Admin\PayrollRunController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StatutoryParameterController;
use App\Http\Controllers\Admin\TaxFormEaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Public Landing Page (Zero CSS, High Conversion, Calculator Demo)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Component Kit Playground for Development & Design QA
Route::get('/demo', function () {
    return view('demo-components');
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
    Route::resource('employees', EmployeeController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::post('employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');

    // 3. Monthly Payroll Runs
    Route::get('/payroll', [PayrollRunController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollRunController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollRun}', [PayrollRunController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payrollRun}/approve', [PayrollRunController::class, 'approve'])->name('payroll.approve');

    // 4. Banking Autopay & Statutory Exporters
    Route::get('/banking', [ExportAndBankingController::class, 'index'])->name('banking.index');
    Route::get('/exports', [ExportAndBankingController::class, 'index'])->name('exports.index');
    Route::post('/banking/{payrollRun}/bank-file', [ExportAndBankingController::class, 'generateBankFile'])->name('banking.bank-file');
    Route::post('/banking/{payrollRun}/statutory-file', [ExportAndBankingController::class, 'generateStatutoryFile'])->name('banking.statutory-file');

    // 5. Year-End Tax Form EA
    Route::get('/tax-ea', [TaxFormEaController::class, 'index'])->name('tax-ea.index');
    Route::post('/tax-ea/compile', [TaxFormEaController::class, 'compileAnnual'])->name('tax-ea.compile');

    // 7. System Governance & Statutory Parameters
    Route::get('/parameters', [StatutoryParameterController::class, 'index'])->name('parameters');
    Route::post('/parameters', [StatutoryParameterController::class, 'store'])->name('parameters.store');
    Route::post('/parameters/departments', [StatutoryParameterController::class, 'storeDepartment'])->name('parameters.departments.store');
    Route::put('/parameters/departments/{department}', [StatutoryParameterController::class, 'updateDepartment'])->name('parameters.departments.update');
    Route::delete('/parameters/departments/{department}', [StatutoryParameterController::class, 'destroyDepartment'])->name('parameters.departments.destroy');
    Route::get('/audit', [AuditTrailController::class, 'index'])->name('audit');

    // 8. User Access & Role Identity Management
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);
});

// Authentication Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
