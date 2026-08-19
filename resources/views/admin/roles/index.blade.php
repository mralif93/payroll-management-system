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

        <!-- Executive Page Hero Banner & Action Suite -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bx-shield-quarter"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Access Roles &amp; Permissions</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $roles->total() }} Roles Active
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Manage system authorization policies, security boundaries, and assign module permissions.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        onclick="openModal('create-role-modal')"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-plus-circle text-base"></i>
                        <span>Add New Role</span>
                    </button>
                </div>
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

        <!-- Executive Search Command Suite for Roles -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 bg-slate-50/50 dark:bg-slate-850/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="bx bx-slider-alt"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Search Roles &amp; Permissions Scope</span>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.roles.index') }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                        <i class="bx bx-reset"></i>
                        <span>Clear Search</span>
                    </a>
                @endif
            </div>

            <div class="p-3.5 sm:p-4">
                <form method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by role title, role key (e.g. payroll_officer), or permission..." 
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition"
                        >
                        @if(request('search'))
                            <a href="{{ route('admin.roles.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="bx bx-x-circle text-base"></i>
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                            <i class="bx bx-filter-alt"></i>
                            <span>Filter</span>
                        </button>
                    </div>

                </form>
            </div>
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
                <table class="w-full text-left text-xs min-w-[780px]">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5 whitespace-nowrap">Role Name</th>
                            <th class="p-3.5 whitespace-nowrap">Role Key</th>
                            <th class="p-3.5 whitespace-nowrap">Description</th>
                            <th class="p-3.5 whitespace-nowrap">Users Assigned</th>
                            <th class="p-3.5 whitespace-nowrap">Granted Permissions</th>
                            <th class="p-3.5 whitespace-nowrap">Type</th>
                            <th class="p-3.5 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($roles as $role)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                    {{ $role->display_name }}
                                </td>
                                <td class="p-3.5 font-mono text-indigo-600 dark:text-indigo-400 font-bold whitespace-nowrap">
                                    {{ $role->name }}
                                </td>
                                <td class="p-3.5 text-slate-500 dark:text-slate-400 max-w-xs truncate whitespace-nowrap">
                                    {{ $role->description ?? '—' }}
                                </td>
                                <td class="p-3.5 font-mono whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $role->users->count() }} users
                                    </span>
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        @foreach($role->permissions->take(4) as $perm)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                        @if($role->permissions->count() > 4)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-500">
                                                +{{ $role->permissions->count() - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    @if($role->is_system)
                                        <x-badge variant="rose" size="xs">System</x-badge>
                                    @else
                                        <x-badge variant="emerald" size="xs">Custom</x-badge>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <x-action-button variant="purple" icon="bx-pencil" title="Edit Role" onclick="openEditRoleModal({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('id')->toArray()) }})">
                                            Edit
                                        </x-action-button>

                                        @if(!$role->is_system && $role->users->count() === 0)
                                            <x-action-button variant="rose" icon="bx-trash" title="Delete Role" onclick="confirmDeleteRole({{ $role->id }}, '{{ addslashes($role->display_name) }}')">
                                                Delete
                                            </x-action-button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($roles->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $roles->links() }}
                </div>
            @endif
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
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('create-role-modal')">
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
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-role-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- UI KIT CONFIRM DELETE ROLE MODAL -->
    <x-confirm-modal 
        id="delete-role-confirm-modal"
        title="Delete Access Role"
        message="Are you sure you want to permanently delete this custom access role? This operation cannot be undone."
        confirmText="Yes, Delete Role"
        confirmVariant="danger"
    />

    <x-slot name="scripts">
        <script>
            function confirmDeleteRole(roleId, roleName) {
                const form = document.getElementById('delete-role-confirm-modal-form');
                form.action = `/admin/roles/${roleId}`;
                document.getElementById('delete-role-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-role-confirm-modal-message').textContent = `Are you sure you want to delete role "${roleName}"?`;
                openModal('delete-role-confirm-modal');
            }

            function openEditRoleModal(role, permIds) {
                const form = document.getElementById('edit-role-form');
                form.action = `/admin/roles/${role.id}`;

                document.getElementById('edit-role-display-name').value = role.display_name || '';
                document.getElementById('edit-role-description').value = role.description || '';

                const checkboxes = document.querySelectorAll('.edit-role-perm-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = permIds.includes(parseInt(cb.value));
                });

                openModal('edit-role-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>
