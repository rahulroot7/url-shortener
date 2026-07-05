<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleId = DB::table('roles')
            ->where('slug', 'super_admin')
            ->value('id');

        DB::statement(
            "INSERT INTO users
            (company_id, role_id, name, email, password, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [
                null,
                $roleId,
                'Super Admin',
                'superadmin@example.com',
                Hash::make('password'),
            ]
        );
    }
}