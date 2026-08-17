<x-layouts.admin title="Access Roles & Permission Matrix">

    <div class="space-y-8">

        <!-- Flash Messages -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" dismissible="true">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Header Banner & Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Access Roles &amp; Permissions</h1>
                    <x-badge variant="purple" dot="true">
                        {{ $roles->total() }} Roles Configured
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Manage system authorization policies, security boundaries, and assign module permissions.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="primary" size="sm" icon="bx-plus-circle" onclick="document.getElementById('create-role-modal').showModal()">
                    Add New Role
                </x-button>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Configured Roles"
                value="{{ $roles->total() }}"
                change="RBAC Security Tiers"
                changeType="positive"
                icon="bx-shield-quarter"
                color="indigo"
            />
            <x-stat-card 
                title="System Permissions"
                value="{{ $permissions->flatten()->count() }}"
                change="Granular Action Checks"
                changeType="neutral"
                icon="bx-key"
                color="purple"
            />
            <x-stat-card 
                title="Protected Modules"
                value="{{ $permissions->count() }}"
                change="Feature Boundaries"
                changeType="neutral"
                icon="bx-cube"
                color="emerald"
            />
            <x-stat-card 
                title="System Reserved"
                value="{{ $roles->where('is_system', true)->count() }}"
                change="Protected baseline roles"
                changeType="neutral"
                icon="bx-lock-alt"
                color="rose"
            />
        </div>

        <!-- Roles Grid / Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-shield-quarter text-purple-600 dark:text-purple-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Role Definition &amp; Access Scope</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Role Name</th>
                            <th class="p-3.5">Role Key</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5">Users Assigned</th>
                            <th class="p-3.5">Granted Permissions</th>
                            <th class="p-3.5">Type</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($roles as $role)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                    {{ $role->display_name }}
                                </td>
                                <td class="p-3.5 font-mono text-indigo-600 dark:text-indigo-400">
                                    {{ $role->name }}
                                </td>
                                <td class="p-3.5 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                    {{ $role->description ?? 'No description provided' }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $role->users->count() }} staff
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                        {{ $role->permissions->count() }} permissions
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    @if($role->is_system)
                                        <x-badge variant="rose" size="sm">System</x-badge>
                                    @else
                                        <x-badge variant="emerald" size="sm">Custom</x-badge>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <x-button variant="secondary" size="xs" onclick="openEditRoleModal({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('id')) }})">
                                            Edit Scope
                                        </x-button>
                                        @if(!$role->is_system && $role->users->count() === 0)
                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Are you sure you want to delete this role?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="ghost" size="xs" type="submit" class="text-rose-600 hover:text-rose-700 dark:text-rose-400">
                                                    <i class="bx bx-trash"></i>
                                                </x-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No roles found. Click "Add New Role" to create access tiers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Create Role Modal -->
    <x-modal id="create-role-modal" title="Define New Access Role" size="lg">
        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4 text-left">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Role Display Name" name="display_name" required placeholder="e.g. Payroll Reviewer" />
                <x-input label="Role Slug/Key" name="name" required placeholder="e.g. payroll_reviewer" />
            </div>

            <x-input label="Role Description" name="description" placeholder="Short summary of access privileges and duties" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Module Permissions Matrix</label>
                <div class="space-y-4 max-h-60 overflow-y-auto p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                    @foreach($permissions as $module => $modulePerms)
                        <div class="space-y-2">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-200 dark:border-slate-800 pb-1">
                                {{ strtoupper($module) }} Module
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($modulePerms as $perm)
                                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                        <input type="checkbox" name="permission_ids[]" value="{{ $perm->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span>{{ $perm->display_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('create-role-modal').close()">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Role
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Role Modal -->
    <x-modal id="edit-role-modal" title="Edit Role & Permission Matrix" size="lg">
        <form id="edit-role-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Role Display Name" name="display_name" id="edit-role-display-name" required />
            <x-input label="Role Description" name="description" id="edit-role-description" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Module Permissions Matrix</label>
                <div class="space-y-4 max-h-60 overflow-y-auto p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                    @foreach($permissions as $module => $modulePerms)
                        <div class="space-y-2">
                            <div class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-200 dark:border-slate-800 pb-1">
                                {{ strtoupper($module) }} Module
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($modulePerms as $perm)
                                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                        <input type="checkbox" name="permission_ids[]" value="{{ $perm->id }}" class="edit-role-perm-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                                        <span>{{ $perm->display_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('edit-role-modal').close()">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-slot name="scripts">
        <script>
            function openEditRoleModal(role, permIds) {
                const form = document.getElementById('edit-role-form');
                form.action = `/admin/roles/${role.id}`;

                document.getElementById('edit-role-display-name').value = role.display_name || '';
                document.getElementById('edit-role-description').value = role.description || '';

                const checkboxes = document.querySelectorAll('.edit-role-perm-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = permIds.includes(parseInt(cb.value));
                });

                document.getElementById('edit-role-modal').showModal();
            }
        </script>
    </x-slot>

</x-layouts.admin>
