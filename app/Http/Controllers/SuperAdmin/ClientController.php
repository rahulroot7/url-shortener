<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Role;

class ClientController extends Controller
{
    public function viewClient()
    {
        $adminRoleId = Role::where('slug', 'super_admin')->value('id');
        $companies = Company::withCount([
                'users',
                'shortUrls',
            ])
            ->withSum('shortUrls', 'hits')
            ->with([
                'users' => function ($query) use ($adminRoleId) {
                    $query->where('role_id', $adminRoleId);
                }
            ])
            ->latest()->paginate(10);
        return view('superadmin.view-client', compact('companies'));

    }
}
