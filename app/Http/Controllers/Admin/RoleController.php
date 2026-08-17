<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of access roles and assigned permission matrix.
     */
    public function index(Request $request): View
    {
        $query = Role::with(['permissions', 'users']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate(10)->withQueryString();
        $permissions = Permission::all()->groupBy('module');

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '_', $validated['name'])),
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permission_ids'])) {
            $role->permissions()->sync($validated['permission_ids']);
        }

        AuditTrail::create([
            'auditable_type' => Role::class,
            'auditable_id' => $role->id,
            'user_id' => auth()->id(),
            'module' => 'access_roles',
            'event' => 'role_created',
            'description' => "Created new access role '{$role->display_name}'",
            'old_values' => null,
            'new_values' => [
                'name' => $role->name,
                'display_name' => $role->display_name,
                'permissions' => $validated['permission_ids'] ?? [],
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->display_name}' created successfully.");
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        $oldState = [
            'display_name' => $role->display_name,
            'description' => $role->description,
            'permissions' => $role->permissions->pluck('id')->toArray(),
        ];

        $role->display_name = $validated['display_name'];
        $role->description = $validated['description'] ?? null;
        $role->save();

        if (isset($validated['permission_ids'])) {
            $role->permissions()->sync($validated['permission_ids']);
        }

        AuditTrail::create([
            'auditable_type' => Role::class,
            'auditable_id' => $role->id,
            'user_id' => auth()->id(),
            'module' => 'access_roles',
            'event' => 'role_updated',
            'description' => "Updated access permissions for role '{$role->display_name}'",
            'old_values' => $oldState,
            'new_values' => [
                'display_name' => $role->display_name,
                'description' => $role->description,
                'permissions' => $validated['permission_ids'] ?? [],
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->display_name}' updated successfully.");
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System reserved roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned active users. Reassign users first.');
        }

        $oldName = $role->display_name;
        $role->permissions()->detach();
        $role->delete();

        AuditTrail::create([
            'auditable_type' => Role::class,
            'auditable_id' => $role->id,
            'user_id' => auth()->id(),
            'module' => 'access_roles',
            'event' => 'role_deleted',
            'description' => "Deleted custom access role '{$oldName}'",
            'old_values' => ['name' => $oldName],
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$oldName}' deleted successfully.");
    }
}
