<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => ['required', 'url'],
        ]);

        $user = auth()->user();

        ShortUrl::create([
            'company_id'  => $user->company_id,
            'user_id'     => $user->id,
            'original_url'=> $request->original_url,
            'short_code'  => Str::random(8),
            'hits'        => 0,
        ]);

        return redirect()->back()->with('success', 'Short URL generated successfully.');
    }

    public function redirect($short_code)
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->firstOrFail();
        $shortUrl->increment('hits');
        return redirect()->away($shortUrl->original_url);
    }

    public function view()
    {
        $shortUrls = ShortUrl::where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(10);
        return view('admin.view-shorturl', compact('shortUrls'));
    }

    public function viewSuperAdmin()
    {
        $shortUrls = ShortUrl::with('company')->latest()->limit(5)->paginate(10);
        return view('superadmin.view-shorturl', compact('shortUrls'));
    }

    public function viewTeamMember()
    {
        $members = User::where('company_id', auth()->user()->company_id)
            ->with('role')
            ->withCount('shortUrls')
            ->withSum('shortUrls', 'hits')
            ->latest()
            ->paginate(10);
        return view('admin.view-team-member', compact('members'));
    }

    public function storeMember(Request $request)
    {
        $request->validate([
            'original_url' => ['required', 'url'],
        ]);

        $user = auth()->user();

        ShortUrl::create([
            'company_id'  => $user->company_id,
            'user_id'     => $user->id,
            'original_url'=> $request->original_url,
            'short_code'  => Str::random(8),
            'hits'        => 0,
        ]);

        return redirect()->back()->with('success', 'Short URL generated successfully.');
    }
}
