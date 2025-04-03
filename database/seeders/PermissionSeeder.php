<?php

// database/seeders/PermissionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage orders',
            'manage products',
            'manage users',
            'manage categories',
            'manage dashboared',
            'manage static blocks',
            'manage static page'
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'slug' => Str::slug($permission),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
