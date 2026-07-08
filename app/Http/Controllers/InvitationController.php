<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Send Invitation
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role'  => 'nullable|in:admin,member',
        ]);

        // Email already registered
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'User already exists.');
        }

        // Pending invitation exists
        if (Invitation::where('email', $request->email)
                ->whereNull('accepted_at')
                ->exists()) {

            return back()->with('error', 'Invitation already sent.');
        }

        // Super Admin

        if ($user->isSuperAdmin()) {
            $role = Role::where('slug', 'admin')->first();
            $invitation = Invitation::create([
                'company_id' => null,
                'role_id' => $request->role_id,
                'invited_by' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'token' => Str::random(64),
            ]);
            Mail::to($invitation->email)->send(new InvitationMail($invitation));
            return back()->with('success', 'Admin invitation sent successfully.');
        }

        // Admin Send invite

        if ($user->isAdmin()) {

            $role = Role::where('slug', $request->role)->first();

            $invitation = Invitation::create([
                'company_id' => auth()->user()->company_id,
                'invited_by' => $user->id,
                'parent_id'  => auth()->id(),
                'role_id'    => 3,
                'name'       => $request->name,
                'email'      => $request->email,
                'token'      => Str::uuid(),
            ]);

            Mail::to($invitation->email)->send(new InvitationMail($invitation));
            return back()->with('success', 'Invitation sent successfully.');
        }

        return back()->with('error', 'Unauthorized action.');

    }

    /**
     * Invitation Form
     */
    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invalid invitation link.');
        }

        if ($invitation->accepted_at) {
            return redirect()->route('login')
                ->with('error', 'Invitation has already been accepted.');
        }

        return view('invitation.accept-invitation', compact('invitation'));
    }

    /**
     * Accept Invitation
     */
    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)->first();
        if (! $invitation) {
            return redirect()->route('login')
                ->with('error', 'Invalid invitation link.');
        }
        if ($invitation->accepted_at) {
            return redirect()->route('login')
                ->with('error', 'Invitation has already been accepted.');
        }
        $rules = [
            'password' => 'required|min:8|confirmed',
        ];

        // Company name required only for Super Admin invitations
        if (is_null($invitation->company_id)) {
            $rules['company_name'] = 'required|string|max:255|unique:companies,name';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            if (is_null($invitation->company_id)) {

                $company = Company::create([
                    'name' => $request->company_name,
                    'slug' => Str::slug($request->company_name),
                ]);

                $companyId = $company->id;

            } else {
                $companyId = $invitation->company_id;
            }

            User::create([
                'company_id' => $companyId,
                'role_id'    => $invitation->role_id,
                'parent_id'  => $invitation->parent_id,
                'name'       => $invitation->name,
                'email'      => $invitation->email,
                'password'   => Hash::make($request->password),
            ]);

            $invitation->update([
                'company_id' => $companyId,
                'accepted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Account created successfully. Please login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}