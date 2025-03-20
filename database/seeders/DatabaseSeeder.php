<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'staff'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
        Admin::firstOrCreate([
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role_id' => Role::where('name', 'Admin')->first()->id,
            'status' => 'active',
        ]);
        Customer::firstOrCreate([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'phone' => '1234567890',
            'status' => 'active',
        ]);
        $this->command->info('✅ Database Seeding Completed!');
    }
}
