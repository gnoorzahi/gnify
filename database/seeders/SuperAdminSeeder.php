<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gnify.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a demo tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo-shop'],
            [
                'name' => 'Demo Shop',
                'description' => 'A demo e-commerce shop',
                'status' => 'active',
                'plan' => 'basic',
                'trial_ends_at' => now()->addDays(30),
            ]
        );

        // Create domain for demo tenant
        Domain::firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'domain' => 'localhost',
                'subdomain' => 'demo'
            ],
            [
                'is_primary' => true,
                'is_https' => false,
                'status' => 'active',
                'verified_at' => now(),
            ]
        );

        $this->command->info('Super admin created:');
        $this->command->info('Email: admin@gnify.com');
        $this->command->info('Password: password');
        $this->command->info('Demo tenant created with domain: demo.localhost');
    }
}
