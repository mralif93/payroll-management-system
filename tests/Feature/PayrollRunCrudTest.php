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

    public function test_calculates_freelance_contractor_and_foreign_worker_correctly(): void
    {
        $dept = Department::first();

        // 1. Independent Contractor / Freelance (Contract for Service)
        $freelancer = Employee::create([
            'company_id' => $this->company->id,
            'department_id' => $dept->id,
            'employee_no' => 'EMP-FREE01',
            'full_name' => 'John Freelancer',
            'nric_passport' => '880101-14-9999',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1988-01-01',
            'joined_date' => '2026-01-01',
            'basic_salary' => 5000.00,
            'designation' => 'External Consultant',
            'employment_status' => 'active',
            'employment_type' => 'freelance_contract',
        ]);

        // 2. Foreign Contract Worker (Non-resident, Category 2 SOCSO, 0% EIS, 2% EPF)
        $foreignWorker = Employee::create([
            'company_id' => $this->company->id,
            'department_id' => $dept->id,
            'employee_no' => 'EMP-FOR01',
            'full_name' => 'Alex Expat',
            'nric_passport' => 'P12345678X',
            'citizenship' => 'foreign_worker',
            'gender' => 'male',
            'birth_date' => '1990-06-15',
            'joined_date' => '2026-03-01',
            'basic_salary' => 4000.00,
            'designation' => 'Senior Specialist',
            'employment_status' => 'active',
            'employment_type' => 'contract_foreign',
        ]);
        $foreignWorker->statutoryProfile()->create([
            'epf_rate_type' => 'custom',
            'epf_employee_custom_rate' => 2.0,
            'epf_employer_custom_rate' => 2.0,
            'socso_category' => 'category_2_injury_only',
            'is_eis_contributed' => false,
            'is_skbbk_contributed' => false,
            'is_tax_resident' => false, // Non-resident => Flat 30%
        ]);

        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();

        // Verify Freelancer item (Zero statutory deductions)
        $freelanceItem = $payrollRun->items()->where('employee_id', $freelancer->id)->first();
        $this->assertNotNull($freelanceItem);
        $this->assertEquals(0.00, $freelanceItem->epf_employee);
        $this->assertEquals(0.00, $freelanceItem->socso_employee);
        $this->assertEquals(0.00, $freelanceItem->eis_employee);
        $this->assertEquals(0.00, $freelanceItem->total_employee_deductions);
        $this->assertEquals(5000.00, $freelanceItem->net_salary);

        // Verify Foreign Worker item (2% EPF = RM80, 0% EE SOCSO, RM50 ER SOCSO (1.25%), 0% EIS, Flat 30% tax = RM1200)
        $foreignItem = $payrollRun->items()->where('employee_id', $foreignWorker->id)->first();
        $this->assertNotNull($foreignItem);
        $this->assertEquals(80.00, $foreignItem->epf_employee);
        $this->assertEquals(0.00, $foreignItem->socso_employee);
        $this->assertEquals(50.00, $foreignItem->socso_employer); // 4000 * 1.25% = 50.00
        $this->assertEquals(0.00, $foreignItem->eis_employee);
        $this->assertEquals(1200.00, $foreignItem->pcb_amount); // Flat 30% of 4000 = 1200.00
        $this->assertEquals(2720.00, $foreignItem->net_salary); // 4000 - 80 - 1200 = 2720.00
    }

    public function test_can_delete_draft_payroll_batch()
    {
        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();
        $this->assertEquals('draft', $payrollRun->status);
        $this->assertGreaterThan(0, $payrollRun->items()->count());

        $response = $this->actingAs($this->admin)->delete(route('admin.payroll.destroy', $payrollRun));
        $response->assertRedirect(route('admin.payroll.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('payroll_runs', ['id' => $payrollRun->id]);
        $this->assertDatabaseCount('payroll_items', 0);
    }

    public function test_cannot_directly_delete_approved_payroll_batch_without_recalculating()
    {
        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();
        $this->actingAs($this->admin)->post(route('admin.payroll.approve', $payrollRun));
        $payrollRun->refresh();
        $this->assertEquals('approved', $payrollRun->status);

        $response = $this->actingAs($this->admin)->delete(route('admin.payroll.destroy', $payrollRun));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('payroll_runs', ['id' => $payrollRun->id]);
    }

    public function test_cannot_recalculate_approved_payroll_batch()
    {
        $this->actingAs($this->admin)->post(route('admin.payroll.store'), [
            'company_id' => $this->company->id,
            'period_year' => '2026',
            'period_month' => '08',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
        ]);

        $payrollRun = PayrollRun::first();
        $this->actingAs($this->admin)->post(route('admin.payroll.approve', $payrollRun));
        $payrollRun->refresh();
        $this->assertEquals('approved', $payrollRun->status);

        $response = $this->actingAs($this->admin)->post(route('admin.payroll.recalculate', $payrollRun));
        $response->assertSessionHas('error');

        $payrollRun->refresh();
        $this->assertEquals('approved', $payrollRun->status);
    }
}
