<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->firstOrFail();
        $memberRole = Role::where('slug', 'member')->firstOrFail();

        $companies = [
            [
                'company' => 'Sembark Tech',
                'admin' => 'Rahul Admin',
                'members' => ['Amit Member', 'Rohit Member'],
            ],
            [
                'company' => 'Microsoft',
                'admin' => 'Vikas Admin',
                'members' => ['Ankit Member', 'Pooja Member'],
            ],
        ];

        foreach ($companies as $index => $data) {

            $company = Company::updateOrCreate(
                ['slug' => Str::slug($data['company'])],
                [
                    'name' => $data['company'],
                ]
            );

            // Company Admin
            User::updateOrCreate(
                ['email' => 'admin' . ($index + 1) . '@example.com'],
                [
                    'name'       => $data['admin'],
                    'password'   => Hash::make('password'),
                    'role_id'    => $adminRole->id,
                    'company_id' => $company->id,
                ]
            );

            // Company Members
            foreach ($data['members'] as $memberName) {

                $email = Str::slug($memberName, '.') . '@example.com';

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'       => $memberName,
                        'password'   => Hash::make('password'),
                        'role_id'    => $memberRole->id,
                        'company_id' => $company->id,
                    ]
                );
            }
        }
    }
}