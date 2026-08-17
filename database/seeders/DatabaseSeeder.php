<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@payroll.my'],
            [
                'staff_id' => 'ADM-001',
                'name' => 'Payroll Officer',
                'role' => 'payroll_officer',
                'status' => 'active',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'superadmin@payroll.my'],
            [
                'staff_id' => 'SA-001',
                'name' => 'Super Administrator',
                'role' => 'super_admin',
                'status' => 'active',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            StatutoryParameterSeeder::class,
        ]);
    }
}
