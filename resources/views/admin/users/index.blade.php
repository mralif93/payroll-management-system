<x-layouts.admin title="User Management & Role Identity">

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
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">User Management</h1>
                    <x-badge variant="indigo" dot="true">
                        {{ $users->total() }} Users
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Manage administrative accounts, role-based access controls (RBAC), and security statuses.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="primary" size="sm" icon="bx-user-plus" onclick="document.getElementById('create-user-modal').showModal()">
                    Add New User
                </x-button>
            </div>
        </div>

        <!-- Metric KPI Highlights -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Total Administrators"
                value="{{ $users->total() }}"
                change="Active System Users"
                changeType="positive"
                icon="bx-user-pin"
                color="indigo"
            />
            <x-stat-card 
                title="Active Roles"
                value="{{ $roles->count() }}"
                change="RBAC Security Matrix"
                changeType="neutral"
                icon="bx-shield-quarter"
                color="purple"
            />
            <x-stat-card 
                title="Active Accounts"
                value="{{ $users->where('status', 'active')->count() }}"
                change="Can access portal"
                changeType="positive"
                icon="bx-check-circle"
                color="emerald"
            />
            <x-stat-card 
                title="Suspended / Inactive"
                value="{{ $users->where('status', '!=', 'active')->count() }}"
                change="Access blocked"
                changeType="negative"
                icon="bx-block"
                color="rose"
            />
        </div>

        <!-- Filter & Search Bar -->
        <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row gap-4 justify-between items-center">
            <form method="GET" action="{{ route('admin.users.index') }}" class="w-full flex flex-col md:flex-row items-center gap-3">
                <div class="w-full md:w-80">
                    <x-input 
                        type="text" 
                        name="search" 
                        placeholder="Search by name, email, or staff ID..." 
                        value="{{ request('search') }}"
                        icon="bx-search" 
                    />
                </div>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <select name="status" class="text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>

                    <select name="role_id" class="text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>

                    <x-button variant="secondary" size="md" type="submit">
                        Filter
                    </x-button>

                    @if(request()->hasAny(['search', 'status', 'role_id']))
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-500 hover:text-indigo-600 dark:text-slate-400 p-2">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-user-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">System User Accounts</h2>
                </div>
                <span class="text-xs text-slate-400 font-mono">Showing {{ $users->count() }} of {{ $users->total() }} accounts</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">User Identity</th>
                            <th class="p-3.5">Staff ID</th>
                            <th class="p-3.5">Assigned Roles</th>
                            <th class="p-3.5">Account Status</th>
                            <th class="p-3.5">Last Login</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs shadow-xs">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block">{{ $user->name }}</span>
                                            <span class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 font-mono text-slate-600 dark:text-slate-300">
                                    {{ $user->staff_id ?? '—' }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @forelse($user->roles as $role)
                                            <x-badge variant="indigo" size="sm">{{ $role->display_name }}</x-badge>
                                        @empty
                                            <span class="text-[11px] text-slate-400 italic">No role assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    @if($user->status === 'active')
                                        <x-badge variant="emerald" dot="true">Active</x-badge>
                                    @elseif($user->status === 'inactive')
                                        <x-badge variant="amber" dot="true">Inactive</x-badge>
                                    @else
                                        <x-badge variant="rose" dot="true">Suspended</x-badge>
                                    @endif
                                </td>
                                <td class="p-3.5 text-slate-500 dark:text-slate-400">
                                    @if($user->last_login_at)
                                        <span class="block">{{ $user->last_login_at->format('d M Y, H:i') }}</span>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $user->last_login_ip }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Never</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <x-button variant="secondary" size="xs" onclick="openEditModal({{ json_encode($user) }}, {{ json_encode($user->roles->pluck('id')) }})">
                                            Edit
                                        </x-button>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to remove this user account?');" class="inline">
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
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    No administrative user accounts found. Click "Add New User" to register an account.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Create User Modal -->
    <x-modal id="create-user-modal" title="Add New Administrative User" size="md">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-left">
            @csrf

            <x-input label="Full Name" name="name" required placeholder="e.g. Siti Nurhaliza" />
            <x-input label="Email Address" name="email" type="email" required placeholder="siti@company.com" />
            <x-input label="Staff ID" name="staff_id" placeholder="e.g. ADM-003" />
            <x-input label="Phone Number" name="phone_number" placeholder="+60123456789" />
            <x-input label="Temporary Password" name="password" type="password" required placeholder="Minimum 8 characters" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Account Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                    <option value="active">Active (Access Granted)</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended (Access Revoked)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assign Roles</label>
                <div class="space-y-2 max-h-36 overflow-y-auto p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $role->display_name }}</span>
                            <span class="text-[10px] text-slate-400">({{ $role->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('create-user-modal').close()">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Create User Account
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit User Modal -->
    <x-modal id="edit-user-modal" title="Edit User Account & Roles" size="md">
        <form id="edit-user-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Full Name" name="name" id="edit-name" required />
            <x-input label="Email Address" name="email" id="edit-email" type="email" required />
            <x-input label="Staff ID" name="staff_id" id="edit-staff-id" />
            <x-input label="Phone Number" name="phone_number" id="edit-phone" />
            <x-input label="Change Password (leave blank to keep current)" name="password" type="password" placeholder="Leave empty to retain password" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Account Status</label>
                <select name="status" id="edit-status" class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                    <option value="active">Active (Access Granted)</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended (Access Revoked)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assign Roles</label>
                <div class="space-y-2 max-h-36 overflow-y-auto p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900" id="edit-roles-container">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="edit-role-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $role->display_name }}</span>
                            <span class="text-[10px] text-slate-400">({{ $role->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('edit-user-modal').close()">
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
            function openEditModal(user, roleIds) {
                const form = document.getElementById('edit-user-form');
                form.action = `/admin/users/${user.id}`;

                document.getElementById('edit-name').value = user.name || '';
                document.getElementById('edit-email').value = user.email || '';
                document.getElementById('edit-staff-id').value = user.staff_id || '';
                document.getElementById('edit-phone').value = user.phone_number || '';
                document.getElementById('edit-status').value = user.status || 'active';

                // Check assigned roles
                const checkboxes = document.querySelectorAll('.edit-role-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = roleIds.includes(parseInt(cb.value));
                });

                document.getElementById('edit-user-modal').showModal();
            }
        </script>
    </x-slot>

</x-layouts.admin>
