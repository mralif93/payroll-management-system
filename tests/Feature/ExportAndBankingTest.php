<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportAndBankingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Company $company;
    protected PayrollRun $payrollRun;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'payroll_officer',
            'display_name' => 'Payroll Officer',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll@payroll.my',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $this->admin->roles()->attach($role);

        $this->company = Company::create([
            'name' => 'PayFlow Tech Sdn Bhd',
            'registration_no' => '202601009999',
            'employer_epf_no' => '123456789',
            'employer_socso_no' => 'A1234567B',
            'employer_tax_no' => 'E1234567890',
        ]);

        $dept = Department::create([
            'company_id' => $this->company->id,
            'name' => 'Technology',
            'code' => 'TECH',
        ]);

        $emp = Employee::create([
            'company_id' => $this->company->id,
            'department_id' => $dept->id,
            'employee_no' => 'EMP-00101',
            'full_name' => 'Muhammad Alif',
            'nric_passport' => '930815-14-1234',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1993-08-15',
            'joined_date' => '2026-01-01',
            'basic_salary' => 6500.00,
            'bank_name' => 'Maybank',
            'bank_account_no' => '514012345678',
            'employment_status' => 'active',
        ]);

        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $this->payrollRun = PayrollRun::first();
    }

    public function test_can_view_banking_and_statutory_exporters_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.banking.index'));
        $response->assertStatus(200);
        $response->assertSee('Bank Autopay & Disbursement');
        $response->assertSee('Maybank2e Multi-Pay');
        $response->assertSee('CIMB BizChannel');

        // Verify Statutory tab
        $exportsResponse = $this->actingAs($this->admin)->get(route('admin.exports.index'));
        $exportsResponse->assertStatus(200);
        $exportsResponse->assertSee('Statutory Agency Exporters');
        $exportsResponse->assertSee('KWSP EPF i-Akaun');
        $exportsResponse->assertSee('PERKESO ASSIST Portal');
    }

    public function test_can_generate_and_download_maybank2e_autopay_file(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.banking.bank-file', $this->payrollRun), [
            'format_type' => 'maybank2e_fixed',
            'download' => '1',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('00HDR', $response->getContent());
        $this->assertDatabaseHas('bank_autopay_batches', [
            'payroll_run_id' => $this->payrollRun->id,
            'format_type' => 'maybank2e_fixed',
        ]);
    }

    public function test_can_generate_and_download_cimb_autopay_csv(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.banking.bank-file', $this->payrollRun), [
            'format_type' => 'cimb_bizchannel_csv',
            'download' => '1',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Employee ID,Full Name,Bank Name', $response->getContent());
    }

    public function test_can_generate_and_download_statutory_epf_csv(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.exports.generate', $this->payrollRun), [
            'statutory_body' => 'epf',
            'download' => '1',
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('Employer No,Employer Name,Contribution Month', $response->getContent());
        $this->assertDatabaseHas('statutory_submissions', [
            'payroll_run_id' => $this->payrollRun->id,
            'statutory_body' => 'epf',
        ]);
    }
}
