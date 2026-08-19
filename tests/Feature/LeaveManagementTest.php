<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Employee $permanentEmployee;
    protected Employee $internEmployee;
    protected LeaveType $annualLeave;
    protected LeaveType $unpaidLeave;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'hr@company.com',
            'status' => 'active',
        ]);

        $company = Company::create([
            'name' => 'PayFlow Test Sdn Bhd',
            'registration_no' => '202601009999',
            'epf_no' => '12345678',
            'socso_no' => 'A12345678',
        ]);

        $dept = Department::create([
            'company_id' => $company->id,
            'name' => 'Engineering',
            'code' => 'ENG',
        ]);

        $this->permanentEmployee = Employee::create([
            'company_id' => $company->id,
            'department_id' => $dept->id,
            'employee_no' => 'EMP-001',
            'full_name' => 'John Doe',
            'nric_passport' => '900101-14-1234',
            'birth_date' => '1990-01-01',
            'joined_date' => '2022-01-01',
            'employment_status' => 'active',
            'employment_type' => 'permanent',
            'basic_salary' => 5200.00,
        ]);

        $this->internEmployee = Employee::create([
            'company_id' => $company->id,
            'department_id' => $dept->id,
            'employee_no' => 'INT-001',
            'full_name' => 'Intern Alex',
            'nric_passport' => '020505-10-5555',
            'birth_date' => '2002-05-05',
            'joined_date' => '2026-06-01',
            'employment_status' => 'active',
            'employment_type' => 'intern',
            'basic_salary' => 1000.00,
        ]);

        $this->annualLeave = LeaveType::create([
            'name' => 'Annual Leave',
            'code' => 'AL',
            'is_paid' => true,
            'default_days_per_year' => 14,
            'color' => 'indigo',
        ]);

        $this->unpaidLeave = LeaveType::create([
            'name' => 'Unpaid Leave',
            'code' => 'UL',
            'is_paid' => false,
            'default_days_per_year' => 0,
            'color' => 'rose',
        ]);
    }

    public function test_admin_can_access_leave_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.leaves.index'));
        $response->assertStatus(200);
        $response->assertSee('Leave Applications &amp; Approvals', false);
    }

    public function test_admin_can_access_leave_entitlements_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.leave-entitlements.index'));
        $response->assertStatus(200);
        $response->assertSee('Leave Entitlements &amp; Quotas', false);
    }

    public function test_admin_can_record_approved_leave_and_update_balance(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.leaves.store'), [
            'employee_id' => $this->permanentEmployee->id,
            'leave_type_id' => $this->annualLeave->id,
            'start_date' => '2026-08-10',
            'end_date' => '2026-08-11',
            'total_days' => 2.0,
            'status' => 'approved',
            'reason' => 'Family vacation',
        ]);

        $response->assertRedirect(route('admin.leaves.index'));
        $this->assertDatabaseHas('leave_applications', [
            'employee_id' => $this->permanentEmployee->id,
            'leave_type_id' => $this->annualLeave->id,
            'total_days' => 2.0,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('employee_leave_balances', [
            'employee_id' => $this->permanentEmployee->id,
            'leave_type_id' => $this->annualLeave->id,
            'taken_days' => 2.0,
            'remaining_days' => 12.0,
        ]);
    }

    public function test_unpaid_leave_automatically_calculates_statutory_orp_salary_deduction(): void
    {
        // 1. Record 2 days Unpaid Leave in August 2026 for John Doe (Basic: RM 5,200.00)
        // EA 1955 ORP Daily Rate = RM 5,200 / 26 days = RM 200.00 / day
        // 2 days unpaid = RM 400.00 deduction
        // Adjusted Gross = RM 5,200 - RM 400 = RM 4,800.00
        LeaveApplication::create([
            'employee_id' => $this->permanentEmployee->id,
            'leave_type_id' => $this->unpaidLeave->id,
            'start_date' => '2026-08-03',
            'end_date' => '2026-08-04',
            'total_days' => 2.0,
            'status' => 'approved',
            'reason' => 'Emergency leave',
        ]);

        $response = $this->actingAs($this->user)->post(route('admin.payroll.store'), [
            'company_id' => $this->permanentEmployee->company_id,
            'period_month' => '08',
            'period_year' => '2026',
            'cutoff_date' => '2026-08-25',
            'payment_date' => '2026-08-28',
            'description' => 'August 2026 Payroll Cycle',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('payroll_items', [
            'employee_id' => $this->permanentEmployee->id,
            'basic_salary' => 5200.00,
            'unpaid_leave_deduction' => 400.00,
            'gross_salary' => 4800.00,
        ]);
    }
}
