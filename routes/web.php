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

    // 2b. Leave Management Subsystem
    Route::get('/leaves', [\App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [\App\Http\Controllers\Admin\LeaveController::class, 'store'])->name('leaves.store');
    Route::patch('/leaves/{leave}/status', [\App\Http\Controllers\Admin\LeaveController::class, 'updateStatus'])->name('leaves.update-status');
    Route::delete('/leaves/{leave}', [\App\Http\Controllers\Admin\LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::put('/leaves/balances/{balance}', [\App\Http\Controllers\Admin\LeaveController::class, 'updateBalance'])->name('leaves.balances.update');

    // 3. Monthly Payroll Runs
    Route::get('/payroll', [PayrollRunController::class, 'index'])->name('payroll.index');
    Route::post('/payroll', [PayrollRunController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollRun}', [PayrollRunController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payrollRun}/approve', [PayrollRunController::class, 'approve'])->name('payroll.approve');
    Route::post('/payroll/{payrollRun}/recalculate', [PayrollRunController::class, 'recalculate'])->name('payroll.recalculate');
    Route::delete('/payroll/{payrollRun}', [PayrollRunController::class, 'destroy'])->name('payroll.destroy');
    Route::get('/payroll/{payrollRun}/payslip/{item}', [PayrollRunController::class, 'payslip'])->name('payroll.payslip');

    // 4. Banking Autopay & Statutory Exporters
    Route::get('/banking', [ExportAndBankingController::class, 'index'])->name('banking.index');
    Route::get('/exports', [ExportAndBankingController::class, 'index'])->name('exports.index');
    Route::post('/banking/{payrollRun}/bank-file', [ExportAndBankingController::class, 'generateBankFile'])->name('banking.bank-file');
    Route::post('/banking/{payrollRun}/statutory-file', [ExportAndBankingController::class, 'generateStatutoryFile'])->name('banking.statutory-file');

    // 5. Year-End Tax Form EA
    Route::get('/tax-ea', [TaxFormEaController::class, 'index'])->name('tax-ea.index');
    Route::post('/tax-ea/compile', [TaxFormEaController::class, 'compileAnnual'])->name('tax-ea.compile');
    Route::get('/tax-ea/{record}/print', [TaxFormEaController::class, 'print'])->name('tax-ea.print');

    // 7. System Governance, Statutory Parameters & Allowances
    Route::get('/parameters', [StatutoryParameterController::class, 'index'])->name('parameters');
    Route::post('/parameters', [StatutoryParameterController::class, 'store'])->name('parameters.store');
    Route::put('/parameters/statutory/{category}', [StatutoryParameterController::class, 'updateStatutoryParameter'])->name('parameters.statutory.update');
    Route::put('/parameters/company', [StatutoryParameterController::class, 'updateCompany'])->name('parameters.company.update');
    Route::post('/parameters/departments', [StatutoryParameterController::class, 'storeDepartment'])->name('parameters.departments.store');
    Route::put('/parameters/departments/{department}', [StatutoryParameterController::class, 'updateDepartment'])->name('parameters.departments.update');
    Route::delete('/parameters/departments/{department}', [StatutoryParameterController::class, 'destroyDepartment'])->name('parameters.departments.destroy');
    Route::post('/parameters/allowances', [StatutoryParameterController::class, 'storeAllowance'])->name('parameters.allowances.store');
    Route::put('/parameters/allowances/{component}', [StatutoryParameterController::class, 'updateAllowance'])->name('parameters.allowances.update');
    Route::delete('/parameters/allowances/{component}', [StatutoryParameterController::class, 'destroyAllowance'])->name('parameters.allowances.destroy');
    Route::post('/parameters/leave-types', [StatutoryParameterController::class, 'storeLeaveType'])->name('parameters.leave-types.store');
    Route::put('/parameters/leave-types/{leaveType}', [StatutoryParameterController::class, 'updateLeaveType'])->name('parameters.leave-types.update');
    Route::delete('/parameters/leave-types/{leaveType}', [StatutoryParameterController::class, 'destroyLeaveType'])->name('parameters.leave-types.destroy');
    Route::get('/audit', [AuditTrailController::class, 'index'])->name('audit');

    // 8. User Access & Role Identity Management
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);

    // 9. UI Component Kit & Pattern Library
    Route::get('/demo', function () {
        return view('demo-components');
    })->name('demo');
});

// Authentication Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
