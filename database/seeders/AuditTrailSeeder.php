<?php

namespace Database\Seeders;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditTrailSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@payroll.my')->first();
        $superAdmin = User::where('email', 'superadmin@payroll.my')->first();

        AuditTrail::create([
            'user_id' => $superAdmin?->id,
            'module' => 'system',
            'event' => 'system.initialized',
            'description' => 'Malaysian statutory parameters (KWSP, SOCSO Act 4, June 2026 SKBBK, EIS, LHDN PCB) initialized.',
            'auditable_type' => null,
            'auditable_id' => null,
            'old_values' => null,
            'new_values' => ['statutory_version' => '2026.06', 'wage_ceiling' => 6000.00],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder/Console',
            'severity' => 'info',
            'created_at' => now()->subHours(2),
        ]);

        AuditTrail::create([
            'user_id' => $admin?->id,
            'module' => 'payroll',
            'event' => 'payroll.batch_reviewed',
            'description' => 'Monthly payroll batch RUN-2026-08-01 reviewed with 48 employees.',
            'auditable_type' => 'App\Models\PayrollRun',
            'auditable_id' => 1,
            'old_values' => ['status' => 'draft'],
            'new_values' => ['status' => 'reviewed', 'total_net' => 148250.00],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'severity' => 'info',
            'created_at' => now()->subMinutes(45),
        ]);

        AuditTrail::create([
            'user_id' => $admin?->id,
            'module' => 'statutory',
            'event' => 'statutory.verified',
            'description' => 'PERKESO SKBBK Lindung 24 Jam deduction schedule verified for August 2026 cycle.',
            'auditable_type' => null,
            'auditable_id' => null,
            'old_values' => null,
            'new_values' => ['skbbk_rate_schedule' => 'Tiered June 2026'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'severity' => 'info',
            'created_at' => now()->subMinutes(20),
        ]);
    }
}
