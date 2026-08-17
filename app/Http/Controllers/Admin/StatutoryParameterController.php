<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\StatutoryParameter;
use App\Services\Payroll\StatutoryParameterResolver;
use Illuminate\Http\Request;

class StatutoryParameterController extends Controller
{
    public function __construct(
        protected StatutoryParameterResolver $parameterResolver
    ) {}

    /**
     * Display current active statutory parameters and gazette versions.
     */
    public function index()
    {
        $parameters = StatutoryParameter::latest('effective_from')->get()->groupBy('category');

        return view('admin.parameters', compact('parameters'));
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

        AuditTrail::log(
            module: 'statutory',
            event: 'statutory.parameter_created',
            description: "Statutory parameter [{$parameter->category}] {$parameter->name} updated for effective date {$parameter->effective_from->toDateString()}.",
            auditable: $parameter,
            newValues: $validated,
            severity: 'warning'
        );

        return redirect()->back()->with('status', 'Statutory parameter policy updated.');
    }
}
