<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator',
            'description' => 'Full access',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@payroll.my',
            'password' => bcrypt('password123'),
            'status' => 'active',
            'staff_id' => 'ADM-001',
        ]);

        $this->admin->roles()->attach($this->role);
    }

    public function test_can_create_user_with_audit_trail(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Alif Tajudin',
            'email' => 'mr.alif.93@gmail.com',
            'staff_id' => '103904',
            'phone_number' => '0133411384',
            'password' => 'password123',
            'status' => 'active',
            'role_ids' => [$this->role->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'mr.alif.93@gmail.com']);
        $this->assertDatabaseHas('audit_trails', [
            'module' => 'users',
            'event' => 'user_created',
        ]);
    }

    public function test_can_update_user(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.my',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'name' => 'John Updated',
            'email' => 'john.updated@test.my',
            'status' => 'inactive',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'john.updated@test.my', 'status' => 'inactive']);
    }

    public function test_can_reset_password(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@test.my',
            'password' => bcrypt('oldpassword'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.users.reset-password', $user), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('audit_trails', [
            'module' => 'users',
            'event' => 'password_reset_by_admin',
        ]);
    }

    public function test_can_toggle_user_status(): void
    {
        $user = User::create([
            'name' => 'Block Test',
            'email' => 'block@test.my',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.users.toggle-status', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'suspended']);
    }

    public function test_can_delete_user(): void
    {
        $user = User::create([
            'name' => 'Delete Test',
            'email' => 'delete@test.my',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
