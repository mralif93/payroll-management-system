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

class PayrollRunCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Company $company;
    protected Employee $employee1;
    protected Employee $employee2;

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
            'name' => 'PayFlow Technologies Sdn Bhd',
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

        $this->employee1 = Employee::create([
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
            'designation' => 'Software Engineer',
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);

        $this->employee2 = Employee::create([
            'company_id' => $this->company->id,
            'department_id' => $dept->id,
            'employee_no' => 'EMP-00102',
            'full_name' => 'Siti Nurhaliza',
            'nric_passport' => '950520-10-5678',
            'citizenship' => 'malaysian',
            'gender' => 'female',
            'birth_date' => '1995-05-20',
            'joined_date' => '2026-02-01',
            'basic_salary' => 4500.00,
            'designation' => 'UI/UX Designer',
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);
    }

    public function test_can_view_payroll_runs_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.payroll.index'));
        $response->assertStatus(200);
        $response->assertSee('Monthly Payroll Runs');
    }

    public function test_can_calculate_and_store_new_payroll_batch(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();
        $this->assertNotNull($payrollRun);
        $this->assertEquals(2, $payrollRun->total_headcount);
        $this->assertEquals(11000.00, $payrollRun->total_gross_amount);
        $this->assertEquals('draft', $payrollRun->status);

        $response->assertRedirect(route('admin.payroll.show', $payrollRun));

        // Assert Line Items were generated
        $this->assertDatabaseHas('payroll_items', [
            'payroll_run_id' => $payrollRun->id,
            'employee_id' => $this->employee1->id,
            'basic_salary' => 6500.00,
        ]);
    }

    public function test_can_view_payroll_run_details_and_payslip_breakdown(): void
    {
        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();

        $response = $this->actingAs($this->admin)->get(route('admin.payroll.show', $payrollRun));
        $response->assertStatus(200);
        $response->assertSee($payrollRun->batch_no);
        $response->assertSee('Muhammad Alif');
        $response->assertSee('Siti Nurhaliza');
    }

    public function test_can_approve_payroll_run_batch(): void
    {
        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();

        $response = $this->actingAs($this->admin)->post(route('admin.payroll.approve', $payrollRun));
        $response->assertRedirect();

        $payrollRun->refresh();
        $this->assertEquals('approved', $payrollRun->status);
        $this->assertEquals($this->admin->id, $payrollRun->approved_by);
        $this->assertNotNull($payrollRun->approved_at);
    }
}
