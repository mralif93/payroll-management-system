<x-layouts.admin title="User Management & Access Control">

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
                    Manage administrative user identities, security statuses, and role-based access permissions.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="primary" size="sm" icon="bx-user-plus" onclick="openModal('create-user-modal')">
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
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <!-- View / Show Profile Action -->
                                        <x-action-button variant="indigo" icon="bx-show" title="View Profile" onclick="openShowModal({{ json_encode($user) }}, {{ json_encode($user->roles) }})">
                                            View
                                        </x-action-button>
                                        
                                        <!-- Edit Action -->
                                        <x-action-button variant="purple" icon="bx-edit" title="Edit User" onclick="openEditModal({{ json_encode($user) }}, {{ json_encode($user->roles->pluck('id')) }})">
                                            Edit
                                        </x-action-button>

                                        <!-- Reset / Change Password Action -->
                                        <x-action-button variant="amber" icon="bx-key" title="Reset / Change Password" onclick="openResetPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')">
                                            Reset Password
                                        </x-action-button>

                                        <!-- Block / Unblock Access Action -->
                                        @if($user->id !== auth()->id())
                                            @if($user->status === 'active')
                                                <x-action-button variant="warning" icon="bx-lock-alt" title="Block / Suspend Access" onclick="confirmToggleStatus({{ $user->id }}, '{{ addslashes($user->name) }}', 'block')">
                                                    Block
                                                </x-action-button>
                                            @else
                                                <x-action-button variant="emerald" icon="bx-lock-open-alt" title="Unblock / Activate Access" onclick="confirmToggleStatus({{ $user->id }}, '{{ addslashes($user->name) }}', 'unblock')">
                                                    Unblock
                                                </x-action-button>
                                            @endif

                                            <!-- Delete Guard Trigger via UI Kit Confirm Modal -->
                                            <x-action-button variant="rose" icon="bx-trash" title="Delete User" onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                Delete
                                            </x-action-button>
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

    <!-- 1. CREATE USER MODAL -->
    <x-modal id="create-user-modal" title="Add New Administrative User" subtitle="Create credentials and assign security permissions" icon="bx-user-plus" size="lg">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-left">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Full Name" name="name" required placeholder="e.g. Siti Nurhaliza" />
                <x-input label="Email Address" name="email" type="email" required placeholder="siti@company.com" />
                <x-input label="Staff Employee ID" name="staff_id" placeholder="e.g. ADM-003" />
                <x-input label="Phone Number" name="phone_number" placeholder="+60123456789" />
            </div>

            <x-input label="Initial Temporary Password" name="password" type="password" required placeholder="Minimum 8 characters" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Account Status</label>
                <select name="status" class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                    <option value="active">Active (Full Portal Access)</option>
                    <option value="inactive">Inactive (Suspended Temporarily)</option>
                    <option value="suspended">Suspended (Access Terminated)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assign Access Roles</label>
                <div class="space-y-2 max-h-36 overflow-y-auto p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $role->display_name }}</span>
                            <span class="text-[10px] text-slate-400">({{ $role->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('create-user-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Create User Account
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 2. EDIT USER MODAL -->
    <x-modal id="edit-user-modal" title="Edit Administrative User" subtitle="Update personal details, credentials, and role assignments" icon="bx-edit" size="lg">
        <form id="edit-user-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Full Name" name="name" id="edit-name" required />
                <x-input label="Email Address" name="email" id="edit-email" type="email" required />
                <x-input label="Staff ID" name="staff_id" id="edit-staff-id" />
                <x-input label="Phone Number" name="phone_number" id="edit-phone" />
            </div>

            <x-input label="Change Password (leave empty to retain current)" name="password" type="password" placeholder="Leave empty to retain existing password" />

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Account Status</label>
                <select name="status" id="edit-status" class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                    <option value="active">Active (Access Granted)</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended (Access Revoked)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Assigned Roles</label>
                <div class="space-y-2 max-h-36 overflow-y-auto p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900" id="edit-roles-container">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="edit-role-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $role->display_name }}</span>
                            <span class="text-[10px] text-slate-400">({{ $role->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-user-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 3. SHOW / VIEW USER DETAILS MODAL (Label | Value Design) -->
    <x-modal id="show-user-modal" title="User Account Details" subtitle="System identity, authorization scope, and login records" icon="bx-user-check" size="lg">
        <div class="space-y-4 text-left text-xs">
            
            <!-- User Top Profile Header -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center text-sm font-extrabold shadow-sm shrink-0" id="show-avatar">
                    US
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate" id="show-name">User Name</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate" id="show-email">email@payroll.my</p>
                </div>
                <div id="show-status-badge" class="shrink-0">
                    <x-badge variant="emerald" dot="true">Active</x-badge>
                </div>
            </div>

            <!-- Structured Label | Value Rows Table (Right Aligned Values) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                
                <!-- Row: Staff ID -->
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Staff Employee ID</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="show-staff-id">ADM-001</div>
                </div>

                <!-- Row: Phone Number -->
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Contact Number</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-phone">+6012-3456789</div>
                </div>

                <!-- Row: Roles -->
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Access Roles</div>
                    <div class="flex items-center justify-end gap-1.5 flex-wrap" id="show-roles-container">
                        <x-badge variant="indigo" size="sm">Super Administrator</x-badge>
                    </div>
                </div>

                <!-- Row: Last Login -->
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Last Login Activity</div>
                    <div class="text-right text-slate-800 dark:text-slate-200" id="show-last-login">Never logged in</div>
                </div>

                <!-- Row: Registered Date -->
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Account Created</div>
                    <div class="text-right text-slate-800 dark:text-slate-200" id="show-created-at">17 Aug 2026</div>
                </div>

            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('show-user-modal')">
                    Close Details
                </x-button>
            </div>
        </div>
    </x-modal>

    <!-- 4. RESET / CHANGE PASSWORD MODAL -->
    <x-modal id="reset-password-modal" title="Reset User Password" subtitle="Assign a new secure password for this administrator" icon="bx-key" iconBg="bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400" size="md">
        <form id="reset-password-form" method="POST" action="" class="space-y-4 text-left">
            @csrf

            <div class="p-3 rounded-xl bg-amber-50/50 dark:bg-amber-950/30 border border-amber-200/60 dark:border-amber-900/60 text-xs">
                <span class="font-bold text-amber-800 dark:text-amber-300 block mb-0.5">Resetting Password For:</span>
                <span class="text-slate-800 dark:text-white font-medium" id="reset-password-user-name">User Name</span>
                <span class="text-slate-400 font-mono text-[11px] block" id="reset-password-user-email">email@payroll.my</span>
            </div>

            <x-input label="New Password" name="password" type="password" required placeholder="Minimum 8 characters" />
            <x-input label="Confirm New Password" name="password_confirmation" type="password" required placeholder="Repeat new password" />

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('reset-password-modal')">
                    Cancel
                </x-button>
                <x-button variant="warning" size="sm" type="submit">
                    Update Password
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 5. STANDARDIZED UI KIT CONFIRM BLOCK / UNBLOCK MODAL -->
    <x-confirm-modal 
        id="toggle-status-confirm-modal"
        title="Change Account Status"
        message="Are you sure you want to change this user's portal access status?"
        confirmText="Confirm Status Change"
        confirmVariant="warning"
        icon="bx-lock-alt"
        iconBg="bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400"
    />

    <!-- 6. STANDARDIZED UI KIT CONFIRM DELETE MODAL -->
    <x-confirm-modal 
        id="delete-user-confirm-modal"
        title="Delete User Account"
        message="Are you sure you want to permanently delete this user account? Their administrative access will be revoked immediately."
        confirmText="Yes, Delete User"
        confirmVariant="danger"
    />

    <x-slot name="scripts">
        <script>
            function openResetPasswordModal(userId, userName, userEmail) {
                const form = document.getElementById('reset-password-form');
                form.action = `/admin/users/${userId}/reset-password`;
                document.getElementById('reset-password-user-name').textContent = userName;
                document.getElementById('reset-password-user-email').textContent = userEmail;
                openModal('reset-password-modal');
            }

            function confirmToggleStatus(userId, userName, action) {
                const form = document.getElementById('toggle-status-confirm-modal-form');
                form.action = `/admin/users/${userId}/toggle-status`;
                document.getElementById('toggle-status-confirm-modal-method').value = 'POST';

                if (action === 'block') {
                    document.getElementById('toggle-status-confirm-modal-title').textContent = 'Block / Suspend User';
                    document.getElementById('toggle-status-confirm-modal-message').textContent = `Are you sure you want to BLOCK "${userName}"? They will be immediately prevented from logging into the system.`;
                    document.getElementById('toggle-status-confirm-modal-btn').textContent = 'Yes, Block User';
                } else {
                    document.getElementById('toggle-status-confirm-modal-title').textContent = 'Unblock / Activate User';
                    document.getElementById('toggle-status-confirm-modal-message').textContent = `Are you sure you want to UNBLOCK "${userName}"? Their active access will be restored immediately.`;
                    document.getElementById('toggle-status-confirm-modal-btn').textContent = 'Yes, Restore Access';
                }

                openModal('toggle-status-confirm-modal');
            }

            function confirmDeleteUser(userId, userName) {
                const form = document.getElementById('delete-user-confirm-modal-form');
                form.action = `/admin/users/${userId}`;
                document.getElementById('delete-user-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-user-confirm-modal-message').textContent = `Are you sure you want to delete user "${userName}"? This action will revoke all portal access.`;
                openModal('delete-user-confirm-modal');
            }

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

                openModal('edit-user-modal');
            }

            function openShowModal(user, roles) {
                document.getElementById('show-avatar').textContent = (user.name || 'US').substring(0, 2).toUpperCase();
                document.getElementById('show-name').textContent = user.name || '—';
                document.getElementById('show-email').textContent = user.email || '—';
                document.getElementById('show-staff-id').textContent = user.staff_id || 'Not Assigned';
                document.getElementById('show-phone').textContent = user.phone_number || 'Not Provided';
                document.getElementById('show-last-login').textContent = user.last_login_at ? new Date(user.last_login_at).toLocaleString() : 'Never logged in';
                document.getElementById('show-created-at').textContent = user.created_at ? new Date(user.created_at).toLocaleDateString() : '—';

                // Status Badge
                const statusContainer = document.getElementById('show-status-badge');
                if (user.status === 'active') {
                    statusContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active</span>';
                } else if (user.status === 'inactive') {
                    statusContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Inactive</span>';
                } else {
                    statusContainer.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Suspended</span>';
                }

                // Roles Badges
                const rolesContainer = document.getElementById('show-roles-container');
                rolesContainer.innerHTML = '';
                if (roles && roles.length > 0) {
                    roles.forEach(role => {
                        const span = document.createElement('span');
                        span.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800';
                        span.textContent = role.display_name;
                        rolesContainer.appendChild(span);
                    });
                } else {
                    rolesContainer.innerHTML = '<span class="text-xs text-slate-400 italic">No access roles assigned.</span>';
                }

                openModal('show-user-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>
