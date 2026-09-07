<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'staff_id' => 'SA-001',
                'name' => 'Yasmin Binti Mohd Zainal Abidin Shukri',
                'email' => 'zainyas@gmail.com',
                'phone_number' => '+01115404621',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'super_admin',
            ],
            [
                'staff_id' => 'ADM-001',
                'name' => 'Payroll Officer',
                'email' => 'admin@payroll.my',
                'phone_number' => '+60123456702',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'payroll_officer',
            ],
            [
                'staff_id' => 'FD-001',
                'name' => 'Finance Director',
                'email' => 'finance@payroll.my',
                'phone_number' => '+60123456703',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'finance_director',
            ],
            [
                'staff_id' => 'AUD-001',
                'name' => 'Internal Auditor',
                'email' => 'auditor@payroll.my',
                'phone_number' => '+60123456704',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'auditor',
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
        }
    }
}
