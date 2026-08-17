<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Seeder;

class CompanyAndDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['registration_no' => '202601009999'],
            [
                'name' => 'PayFlow Technologies Sdn Bhd',
                'epf_no' => '123456789',
                'socso_no' => 'A123456789',
                'tax_no' => 'E 9876543200',
                'hrd_no' => 'HRD-2026-999',
                'bank_name' => 'Malayan Banking Berhad (Maybank)',
                'bank_account_no' => '514012345678',
                'contact_person' => 'Ahmad Tajudin',
                'contact_email' => 'admin@payroll.my',
                'contact_phone' => '+603-88889999',
                'address' => 'Level 28, Menara PayFlow, KLCC, 50088 Kuala Lumpur, Malaysia',
                'is_active' => true,
            ]
        );

        $departments = [
            ['name' => 'Executive & Management', 'code' => 'EXEC'],
            ['name' => 'Engineering & Technology', 'code' => 'TECH'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN'],
            ['name' => 'Human Resources & Admin', 'code' => 'HRA'],
            ['name' => 'Sales & Business Development', 'code' => 'SBD'],
            ['name' => 'Marketing & Communications', 'code' => 'MKT'],
            ['name' => 'Customer Operations & Support', 'code' => 'OPS'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['company_id' => $company->id, 'name' => $dept['name']],
                ['code' => $dept['code']]
            );
        }
    }
}
