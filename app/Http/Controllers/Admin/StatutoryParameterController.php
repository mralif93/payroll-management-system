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

        return view('admin.parameters', compact('parameters', 'company', 'departments', 'salaryComponents'));
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
}
