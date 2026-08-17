<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of administrative users, roles, and security statuses.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('staff_id', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($roleId = $request->input('role_id')) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Store a newly created administrator or system user with assigned roles.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'staff_id' => $validated['staff_id'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        }

        // Compliance audit trail log
        AuditTrail::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'user_id' => auth()->id(),
            'event' => 'user_created',
            'old_values' => null,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->staff_id,
                'status' => $user->status,
                'roles' => $validated['role_ids'] ?? [],
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User account for {$user->name} created successfully.");
    }

    /**
     * Update user details, role assignments, or account status.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'staff_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $oldState = [
            'name' => $user->name,
            'email' => $user->email,
            'staff_id' => $user->staff_id,
            'status' => $user->status,
            'roles' => $user->roles->pluck('id')->toArray(),
        ];

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->staff_id = $validated['staff_id'] ?? null;
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (isset($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        }

        // Compliance audit trail log
        AuditTrail::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'user_id' => auth()->id(),
            'event' => 'user_updated',
            'old_values' => $oldState,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->staff_id,
                'status' => $user->status,
                'roles' => $validated['role_ids'] ?? [],
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'info',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User account {$user->name} updated successfully.");
    }

    /**
     * Delete or deactivate user account with safety check.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own logged-in account.');
        }

        $oldName = $user->name;
        $user->delete();

        AuditTrail::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'user_id' => auth()->id(),
            'event' => 'user_deleted',
            'old_values' => ['name' => $oldName, 'email' => $user->email],
            'new_values' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$oldName} deleted successfully.");
    }

    /**
     * Direct password reset / change for an administrative user.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        AuditTrail::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'user_id' => auth()->id(),
            'event' => 'password_reset_by_admin',
            'old_values' => null,
            'new_values' => ['user_id' => $user->id, 'email' => $user->email],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => 'warning',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Password for user {$user->name} has been successfully reset.");
    }

    /**
     * Block, suspend, or unblock user access.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot block your own logged-in administrator account.');
        }

        $oldStatus = $user->status;
        $newStatus = $oldStatus === 'active' ? 'suspended' : 'active';

        $user->status = $newStatus;
        $user->save();

        $actionText = $newStatus === 'suspended' ? 'blocked / suspended' : 'unblocked / activated';

        AuditTrail::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'user_id' => auth()->id(),
            'event' => $newStatus === 'suspended' ? 'user_blocked' : 'user_unblocked',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'severity' => $newStatus === 'suspended' ? 'warning' : 'info',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User account {$user->name} has been {$actionText}.");
    }
}
