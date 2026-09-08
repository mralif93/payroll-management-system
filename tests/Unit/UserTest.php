<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_assignment_and_checks(): void
    {
        $role = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator',
            'description' => 'Full access',
            'is_system' => true,
        ]);

        $user = User::create([
            'name' => 'Unit Test User',
            'email' => 'unit@test.my',
            'password' => 'secret123',
            'status' => 'active',
            'staff_id' => 'ADM-999',
        ]);

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertTrue($user->canManagePayroll());
        $this->assertTrue($user->isActive());
        $this->assertEquals('ADM-999', $user->employee_code);
    }

    public function test_user_password_is_hashed_automatically(): void
    {
        $user = User::create([
            'name' => 'Hashing Test',
            'email' => 'hash@test.my',
            'password' => 'plaintext123',
            'status' => 'active',
        ]);

        $this->assertNotEquals('plaintext123', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('plaintext123', $user->password));
    }
}
