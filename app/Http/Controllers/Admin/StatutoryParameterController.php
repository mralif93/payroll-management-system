<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Company;
use App\Models\Department;
use App\Models\StatutoryParameter;
use App\Services\Payroll\StatutoryParameterResolver;
use Illuminate\Http\Request;

class StatutoryParameterController extends Controller
{
    public function __construct(
        protected StatutoryParameterResolver $parameterResolver
    ) {}

    /**
     * Display current active statutory parameters, company profile, and departments roster.
     */
    public function index()
    {
        $parameters = StatutoryParameter::latest('effective_from')->get()->groupBy('category');
        $company = Company::first();
        $departments = Department::withCount('employees')->orderBy('name')->get();
        $salaryComponents = \App\Models\SalaryComponent::withCount('employeeSalaryComponents')->orderBy('type')->orderBy('name')->get();
        $leaveTypes = \App\Models\LeaveType::withCount('applications')->orderBy('name')->get();

        return view('admin.parameters', compact('parameters', 'company', 'departments', 'salaryComponents', 'leaveTypes'));
    }

    /**
     * Update corporate company profile.
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_no' => ['required', 'string', 'max:50'],
            'epf_no' => ['nullable', 'string', 'max:50'],
            'socso_no' => ['nullable', 'string', 'max:50'],
            'tax_no' => ['nullable', 'string', 'max:50'],
            'hrd_no' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_no' => ['nullable', 'string', 'max:50'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $company = Company::first();
        if (!$company) {
            $company = Company::create($validated);
        } else {
            $oldValues = $company->only(array_keys($validated));
            $company->update($validated);
        }

        AuditTrail::create([
            'auditable_type' => Company::class,
            'auditable_id' => $company->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'company_profile_updated',
            'description' => "Updated corporate profile for '{$company->name}'",
            'old_values' => $oldValues ?? null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', 'Company profile updated successfully.');
    }

    /**
     * Store or override a statutory parameter gazette rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:epf,socso,skbbk,eis,pcb,hrd'],
            'parameter_key' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'value_payload' => ['required', 'array'],
            'effective_from' => ['required', 'date'],
            'reference_gazette' => ['nullable', 'string'],
        ]);

        $parameter = StatutoryParameter::create($validated);
        $this->parameterResolver->flushCache();

        AuditTrail::create([
            'auditable_type' => StatutoryParameter::class,
            'auditable_id' => $parameter->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'parameter_created',
            'description' => "Statutory parameter [{$parameter->category}] {$parameter->name} updated for effective date {$parameter->effective_from->toDateString()}.",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->back()->with('success', 'Statutory parameter policy updated.');
    }

    /**
     * Update specific statutory policy values (EPF, SOCSO/SKBBK, EIS, PCB).
     */
    public function updateStatutoryParameter(Request $request, string $category)
    {
        $payload = $request->input('value_payload', []);
        $referenceGazette = $request->input('reference_gazette');

        $statutory = StatutoryParameter::where('category', $category)->latest('effective_from')->first();

        if ($statutory) {
            $oldValues = $statutory->value_payload;
            $newValues = array_merge($oldValues ?? [], $payload);
            $statutory->value_payload = $newValues;
            if ($referenceGazette) {
                $statutory->reference_gazette = $referenceGazette;
            }
            $statutory->save();
        } else {
            $statutory = StatutoryParameter::create([
                'category' => $category,
                'parameter_key' => "{$category}_standard_rules",
                'name' => strtoupper($category) . ' Statutory Parameters',
                'description' => "Standard configuration for {$category}",
                'value_payload' => $payload,
                'effective_from' => '2024-01-01',
                'reference_gazette' => $referenceGazette ?? 'Official Gazette',
                'is_active' => true,
            ]);
        }

        $this->parameterResolver->flushCache();

        AuditTrail::create([
            'auditable_type' => StatutoryParameter::class,
            'auditable_id' => $statutory->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'statutory_rate_updated',
            'description' => "Updated statutory policy rates for " . strtoupper($category),
            'old_values' => $oldValues ?? null,
            'new_values' => $payload,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.parameters')->with('success', strtoupper($category) . ' statutory parameters updated successfully.');
    }

    /**
     * Store a newly created corporate department.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        $department = Department::create($validated);

        AuditTrail::create([
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'department_created',
            'description' => "Created organizational department '{$department->name}' ({$department->code})",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Department '{$department->name}' added successfully.");
    }

    /**
     * Update department details.
     */
    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        $oldValues = $department->only(['name', 'code']);
        $department->update($validated);

        AuditTrail::create([
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'department_updated',
            'description' => "Updated department '{$department->name}'",
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Department '{$department->name}' updated successfully.");
    }

    /**
     * Delete a department if no employees are assigned.
     */
    public function destroyDepartment(Request $request, Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('admin.parameters')
                ->with('error', "Cannot delete department '{$department->name}' because {$department->employees()->count()} active employees are assigned to it.");
        }

        $oldName = $department->name;
        $department->delete();

        AuditTrail::create([
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'department_deleted',
            'description' => "Deleted department '{$oldName}'",
            'old_values' => ['name' => $oldName],
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Department '{$oldName}' deleted successfully.");
    }

    /**
     * Store a newly created salary allowance component.
     */
    public function storeAllowance(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:salary_components,code'],
            'type' => ['required', 'in:allowance,earning,deduction,reimbursement'],
            'is_epf_subject' => ['nullable', 'boolean'],
            'is_socso_subject' => ['nullable', 'boolean'],
            'is_eis_subject' => ['nullable', 'boolean'],
            'is_pcb_subject' => ['nullable', 'boolean'],
        ]);

        $validated['is_epf_subject'] = $request->boolean('is_epf_subject');
        $validated['is_socso_subject'] = $request->boolean('is_socso_subject');
        $validated['is_eis_subject'] = $request->boolean('is_eis_subject');
        $validated['is_pcb_subject'] = $request->boolean('is_pcb_subject');
        $validated['is_active'] = true;

        $component = \App\Models\SalaryComponent::create($validated);

        AuditTrail::create([
            'auditable_type' => \App\Models\SalaryComponent::class,
            'auditable_id' => $component->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'allowance_created',
            'description' => "Created salary component/allowance '{$component->name}' ({$component->code})",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Allowance component '{$component->name}' created successfully.");
    }

    /**
     * Update an existing salary allowance component.
     */
    public function updateAllowance(Request $request, \App\Models\SalaryComponent $component)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'is_epf_subject' => ['nullable', 'boolean'],
            'is_socso_subject' => ['nullable', 'boolean'],
            'is_eis_subject' => ['nullable', 'boolean'],
            'is_pcb_subject' => ['nullable', 'boolean'],
        ]);

        $validated['is_epf_subject'] = $request->boolean('is_epf_subject');
        $validated['is_socso_subject'] = $request->boolean('is_socso_subject');
        $validated['is_eis_subject'] = $request->boolean('is_eis_subject');
        $validated['is_pcb_subject'] = $request->boolean('is_pcb_subject');

        $oldValues = $component->only(['name', 'is_epf_subject', 'is_socso_subject', 'is_eis_subject', 'is_pcb_subject']);
        $component->update($validated);

        AuditTrail::create([
            'auditable_type' => \App\Models\SalaryComponent::class,
            'auditable_id' => $component->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'allowance_updated',
            'description' => "Updated salary component/allowance '{$component->name}' ({$component->code})",
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Allowance '{$component->name}' updated successfully.");
    }

    /**
     * Delete an existing salary allowance component.
     */
    public function destroyAllowance(Request $request, \App\Models\SalaryComponent $component)
    {
        $name = $component->name;
        $code = $component->code;

        $component->delete();

        AuditTrail::create([
            'auditable_type' => \App\Models\SalaryComponent::class,
            'auditable_id' => $component->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'allowance_deleted',
            'description' => "Deleted salary allowance component '{$name}' ({$code})",
            'old_values' => null,
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Allowance component '{$name}' removed.");
    }

    /**
     * Store new leave type configuration.
     */
    public function storeLeaveType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:leave_types,code'],
            'is_paid' => ['nullable', 'boolean'],
            'default_days_per_year' => ['required', 'integer', 'min:0', 'max:365'],
            'color' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_paid'] = $request->boolean('is_paid');
        $validated['color'] = $validated['color'] ?? 'indigo';

        $leaveType = \App\Models\LeaveType::create($validated);

        // Populate baseline balances for active employees
        $currentYear = (int) date('Y');
        $employees = \App\Models\Employee::where('employment_status', '!=', 'resigned')->get();
        foreach ($employees as $employee) {
            $quota = ($employee->employment_type === 'intern' && $leaveType->code !== 'AL' && $leaveType->code !== 'MC') ? 0 : $leaveType->default_days_per_year;
            \App\Models\EmployeeLeaveBalance::firstOrCreate(
                ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id, 'year' => $currentYear],
                ['total_entitled' => $quota, 'taken_days' => 0.0, 'pending_days' => 0.0, 'remaining_days' => $quota]
            );
        }

        AuditTrail::create([
            'auditable_type' => \App\Models\LeaveType::class,
            'auditable_id' => $leaveType->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'leave_type_created',
            'description' => "Created statutory leave type '{$leaveType->name}' ({$leaveType->code}) with {$leaveType->default_days_per_year} default days",
            'old_values' => null,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Leave type '{$leaveType->name}' created successfully.");
    }

    /**
     * Update leave type configuration.
     */
    public function updateLeaveType(Request $request, \App\Models\LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:leave_types,code,' . $leaveType->id],
            'is_paid' => ['nullable', 'boolean'],
            'default_days_per_year' => ['required', 'integer', 'min:0', 'max:365'],
            'color' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_paid'] = $request->boolean('is_paid');
        $oldValues = $leaveType->only(['name', 'code', 'is_paid', 'default_days_per_year', 'description']);

        $leaveType->update($validated);

        AuditTrail::create([
            'auditable_type' => \App\Models\LeaveType::class,
            'auditable_id' => $leaveType->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'leave_type_updated',
            'description' => "Updated leave category '{$leaveType->name}' ({$leaveType->code})",
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Leave category '{$leaveType->name}' updated successfully.");
    }

    /**
     * Delete leave type configuration.
     */
    public function destroyLeaveType(Request $request, \App\Models\LeaveType $leaveType)
    {
        $name = $leaveType->name;
        $code = $leaveType->code;

        $leaveType->delete();

        AuditTrail::create([
            'auditable_type' => \App\Models\LeaveType::class,
            'auditable_id' => $leaveType->id,
            'user_id' => auth()->id(),
            'module' => 'parameters',
            'event' => 'leave_type_deleted',
            'description' => "Deleted leave category '{$name}' ({$code})",
            'old_values' => null,
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.parameters')->with('success', "Leave category '{$name}' removed.");
    }
}
