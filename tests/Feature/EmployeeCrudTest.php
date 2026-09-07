<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Company $company;
    protected Department $dept;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'hr_manager',
            'display_name' => 'HR Manager',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'name' => 'HR Admin',
            'email' => 'hr@payroll.my',
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

        $this->dept = Department::create([
            'company_id' => $this->company->id,
            'name' => 'Engineering',
            'code' => 'ENG',
        ]);
    }

    public function test_can_register_employee_with_statutory_profile_and_audit(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), [
            'company_id' => $this->company->id,
            'department_id' => $this->dept->id,
            'employee_no' => 'EMP-00101',
            'full_name' => 'Ahmad Tajudin',
            'nric_passport' => '900101-14-1234',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'joined_date' => '2026-01-01',
            'basic_salary' => 5500.00,
            'designation' => 'Lead Developer',
            'bank_name' => 'Maybank',
            'bank_account_no' => '514012345678',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['employee_no' => 'EMP-00101', 'full_name' => 'Ahmad Tajudin']);
        $this->assertDatabaseHas('employee_statutory_profiles', ['epf_rate_type' => 'standard_11']);
        $this->assertDatabaseHas('audit_trails', ['module' => 'employees', 'event' => 'employee_registered']);
    }

    public function test_can_update_employee(): void
    {
        $emp = Employee::create([
            'company_id' => $this->company->id,
            'employee_no' => 'EMP-00102',
            'full_name' => 'Siti Nur',
            'nric_passport' => '920202-10-5678',
            'citizenship' => 'malaysian',
            'gender' => 'female',
            'birth_date' => '1992-02-02',
            'joined_date' => '2026-02-01',
            'basic_salary' => 4500.00,
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.employees.update', $emp), [
            'full_name' => 'Siti Nurhaliza',
            'nric_passport' => '920202-10-5678',
            'birth_date' => '1992-02-02',
            'gender' => 'female',
            'citizenship' => 'malaysian',
            'designation' => 'Senior Accountant',
            'basic_salary' => 5200.00,
            'joined_date' => '2026-02-01',
            'employment_status' => 'confirmed',
            'employment_type' => 'permanent',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['id' => $emp->id, 'full_name' => 'Siti Nurhaliza', 'basic_salary' => 5200.00]);
    }

    public function test_can_toggle_employee_status(): void
    {
        $emp = Employee::create([
            'company_id' => $this->company->id,
            'employee_no' => 'EMP-00103',
            'full_name' => 'Faizal Tahir',
            'nric_passport' => '880303-08-9999',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1988-03-03',
            'joined_date' => '2026-03-01',
            'basic_salary' => 6000.00,
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.employees.toggle-status', $emp));

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['id' => $emp->id, 'employment_status' => 'resigned']);
    }

    public function test_can_delete_employee(): void
    {
        $emp = Employee::create([
            'company_id' => $this->company->id,
            'employee_no' => 'EMP-00104',
            'full_name' => 'Kamal Adli',
            'nric_passport' => '870404-01-1111',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1987-04-04',
            'joined_date' => '2026-04-01',
            'basic_salary' => 4000.00,
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.employees.destroy', $emp));

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseMissing('employees', ['id' => $emp->id]);
    }

    public function test_can_view_statutory_profiles(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.employees.statutory'));
        $response->assertOk();
        $response->assertSee('Salary &amp; Statutory Profiles', false);
    }

    public function test_can_update_statutory_and_compensation_profile(): void
    {
        $emp = Employee::create([
            'company_id' => $this->company->id,
            'employee_no' => 'EMP-00105',
            'full_name' => 'Dr. Latif',
            'nric_passport' => '850505-10-2222',
            'citizenship' => 'malaysian',
            'gender' => 'male',
            'birth_date' => '1985-05-05',
            'joined_date' => '2026-05-01',
            'basic_salary' => 7000.00,
            'employment_status' => 'active',
            'employment_type' => 'permanent',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.employees.update-statutory', $emp), [
            'basic_salary' => 7500.00,
            'bank_name' => 'CIMB Bank',
            'bank_account_no' => '8601234567',
            'epf_member_no' => 'EPF-998877',
            'epf_rate_type' => 'reduced_9',
            'socso_member_no' => 'SOCSO-112233',
            'socso_category' => 'category_1_full',
            'is_eis_contributed' => 1,
            'is_skbbk_contributed' => 1,
            'income_tax_no' => 'SG9988776600',
            'tax_category' => 'married_working',
            'number_of_children' => 2,
            'is_tax_resident' => 1,
            'is_disabled' => 0,
            'spouse_is_disabled' => 0,
            'monthly_zakat_amount' => 50.00,
            'total_tp1_relief_amount' => 0.00,
        ]);

        $response->assertRedirect(route('admin.employees.statutory'));
        $this->assertDatabaseHas('employees', ['id' => $emp->id, 'basic_salary' => 7500.00, 'bank_name' => 'CIMB Bank']);
        $this->assertDatabaseHas('employee_statutory_profiles', [
            'employee_id' => $emp->id,
            'epf_rate_type' => 'reduced_9',
            'is_skbbk_contributed' => 1,
            'tax_category' => 'married_working',
            'number_of_children' => 2,
        ]);
    }
}
