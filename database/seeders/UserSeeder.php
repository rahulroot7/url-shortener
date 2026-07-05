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
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Member',
                'email' => 'member@example.com',
                'role' => 'member',
            ],
        ];

        foreach ($users as $user) {

            $role = Role::where('slug', $user['role'])->first();

            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'company_id' => null,
                    'role_id'    => $role->id,
                    'name'       => $user['name'],
                    'password'   => Hash::make('password'),
                ]
            );
        }
    }
}