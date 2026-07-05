<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Role;
use App\Models\ShortUrl;
use App\Models\User;
use App\Exports\ShortUrlsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminShortUrlsExport;
use App\Exports\MemberShortUrlsExport;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {

            $superAdminRoleId = Role::where('slug', 'super_admin')->value('id');
            $adminRoleId = Role::where('slug', 'admin')->value('id');

            $companies = Company::withCount([
                    'users',
                    'shortUrls',
                ])
                ->withSum('shortUrls', 'hits')
                ->with([
                    'users' => function ($query) use ($superAdminRoleId) {
                        $query->where('role_id', $superAdminRoleId);
                    }
                ])
                ->latest()
                ->limit(5)
                ->get();

            $query = ShortUrl::with('company');

            switch ($request->filter ?? 'this_month') {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'last_week':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ]);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_month':
                default:
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $shortUrls = $query
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.super-admin', compact(
                'companies',
                'shortUrls',
                'adminRoleId'
            ));
        }

        if ($user->isAdmin()) {
            $query = ShortUrl::with('user')
                ->where('company_id', $user->company_id);

            switch ($request->filter ?? 'this_month') {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'last_week':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ]);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_month':
                default:
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $shortUrls = $query
                ->latest()
                ->limit(5)
                ->get();
            

            $members = User::where('company_id', $user->company_id)
                ->with('role')
                ->withCount('shortUrls')
                ->withSum('shortUrls', 'hits')
                ->latest()
                ->get();

            $roles = Role::select('id', 'name')->where('slug', '!=', 'super_admin')->get();
            return view('dashboard.admin', compact('shortUrls', 'members', 'roles'));
        }

        if ($user->isMember()) {
            $query = ShortUrl::with('company')
                ->where('user_id', auth()->id());

            switch ($request->filter ?? 'this_month') {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'last_week':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ]);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_month':
                default:
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $shortUrls = $query
                ->latest()
                ->paginate(10)
                ->withQueryString();
            return view('dashboard.member',compact('shortUrls'));
        }

        abort(403);
    }

    //Filter
    public function viewFiltter(Request $request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {

            $query = ShortUrl::with('company');

            switch ($request->filter ?? 'this_month') {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'last_week':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ]);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_month':
                default:
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $shortUrls = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

            return view('superadmin.view-shorturl', compact('shortUrls'));
        }
    }

    public function download(Request $request)
    {
        return Excel::download(
            new ShortUrlsExport($request->filter),
            'short_urls.xlsx'
        );
    }

    public function viewAdminUrlFiltter(Request $request)
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $query = ShortUrl::with('user')
                ->where('company_id', $user->company_id);

            switch ($request->filter ?? 'this_month') {

                case 'today':
                    $query->whereDate('created_at', today());
                    break;

                case 'last_week':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek(),
                    ]);
                    break;

                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year);
                    break;

                case 'this_month':
                default:
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $shortUrls = $query
                ->latest()
                ->paginate(10)
                ->withQueryString();
            return view('admin.view-shorturl', compact('shortUrls'));
        }
    }

    public function downloadAdmin(Request $request)
    {
        return Excel::download(
            new AdminShortUrlsExport(
                auth()->user(),
                $request->filter
            ),
            'admin-short-urls.xlsx'
        );
    }

    public function downloadMember(Request $request)
    {
        return Excel::download(
            new MemberShortUrlsExport(auth()->user(), $request->filter),
            'member-short-urls.xlsx'
        );
    }
}