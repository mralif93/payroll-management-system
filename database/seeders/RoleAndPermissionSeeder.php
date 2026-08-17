<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Permissions Catalog
        $permissions = [
            // Payroll Module
            ['name' => 'payroll.view', 'display_name' => 'View Payroll Operations', 'module' => 'payroll'],
            ['name' => 'payroll.create', 'display_name' => 'Initiate Monthly Payroll Run', 'module' => 'payroll'],
            ['name' => 'payroll.calculate', 'display_name' => 'Execute Statutory Computations', 'module' => 'payroll'],
            ['name' => 'payroll.approve', 'display_name' => 'Approve Payroll Batches', 'module' => 'payroll'],
            ['name' => 'payroll.lock', 'display_name' => 'Lock Payroll Audit Run', 'module' => 'payroll'],

            // Employee Module
            ['name' => 'employees.view', 'display_name' => 'View Employee Directory', 'module' => 'employees'],
            ['name' => 'employees.create', 'display_name' => 'Register New Employee', 'module' => 'employees'],
            ['name' => 'employees.edit', 'display_name' => 'Update Employee & Salary Profiles', 'module' => 'employees'],

            // Statutory & Bank Exports
            ['name' => 'statutory.view', 'display_name' => 'View Statutory Parameters', 'module' => 'statutory'],
            ['name' => 'statutory.edit', 'display_name' => 'Update Statutory Rate Gazettes', 'module' => 'statutory'],
            ['name' => 'exports.statutory', 'display_name' => 'Export EPF, SOCSO & CP39 Files', 'module' => 'exports'],
            ['name' => 'exports.bank', 'display_name' => 'Generate Bank Autopay Files (M2E/CIMB)', 'module' => 'exports'],
            ['name' => 'tax.ea_form', 'display_name' => 'Generate Year-End Form EA', 'module' => 'tax'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        // 2. Roles Catalog
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Unrestricted access to all payroll modules, statutory parameters, and governance.',
                'is_system' => true,
            ]
        );
        $superAdminRole->permissions()->sync(Permission::all());

        $payrollOfficerRole = Role::updateOrCreate(
            ['name' => 'payroll_officer'],
            [
                'display_name' => 'Payroll Officer',
                'description' => 'Executes monthly calculations, verifies statutory items, and exports bank/statutory files.',
                'is_system' => true,
            ]
        );
        $payrollOfficerRole->permissions()->sync(
            Permission::whereIn('name', [
                'payroll.view', 'payroll.create', 'payroll.calculate',
                'employees.view', 'employees.create', 'employees.edit',
                'statutory.view', 'exports.statutory', 'exports.bank', 'tax.ea_form'
            ])->pluck('id')
        );

        $financeDirectorRole = Role::updateOrCreate(
            ['name' => 'finance_director'],
            [
                'display_name' => 'Finance Director',
                'description' => 'Reviews financial summaries, approves monthly disbursements, and locks audit runs.',
                'is_system' => true,
            ]
        );
        $financeDirectorRole->permissions()->sync(
            Permission::whereIn('name', [
                'payroll.view', 'payroll.approve', 'payroll.lock',
                'statutory.view', 'exports.statutory', 'exports.bank', 'tax.ea_form'
            ])->pluck('id')
        );

        $auditorRole = Role::updateOrCreate(
            ['name' => 'auditor'],
            [
                'display_name' => 'Internal / Statutory Auditor',
                'description' => 'Read-only compliance verification for payslips, EA forms, and audit trails.',
                'is_system' => true,
            ]
        );
        $auditorRole->permissions()->sync(
            Permission::whereIn('name', ['payroll.view', 'employees.view', 'statutory.view'])->pluck('id')
        );

        // 3. Attach Roles to Default Seeded Users
        $adminUser = User::where('email', 'admin@payroll.my')->first();
        if ($adminUser) {
            $adminUser->roles()->sync([$payrollOfficerRole->id]);
        }

        $superAdminUser = User::where('email', 'superadmin@payroll.my')->first();
        if ($superAdminUser) {
            $superAdminUser->roles()->sync([$superAdminRole->id]);
        }
    }
}
