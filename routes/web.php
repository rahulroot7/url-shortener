<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdmin\ClientController;
use App\Http\Controllers\Admin\ShortUrlController;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/generate', [ShortUrlController::class, 'store'])->name('short-url.store')->middleware('role:admin,member');
});
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->group(function () {
    Route::post('/invitations', [InvitationController::class, 'store'])->name('superadmin.invitations.store');
    Route::get('/view-generate-urls', [ShortUrlController::class, 'viewSuperAdmin'])->name('superadmin.short-url.view');
    Route::get('/view-clients', [ClientController::class, 'viewClient'])->name('superadmin.viewClient.view');
    Route::get('/super-admin/short-urls/download', [DashboardController::class, 'download'])->name('super-admin.short-urls.download');
    Route::get('/super-admin/short-urls', [DashboardController::class, 'index'])->name('superadmin.short-urls.filter');
    Route::get('/super-admin/view-short-urls', [DashboardController::class, 'viewFiltter'])->name('superadmin.short-urls.viewFiltter');

});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::post('/generate', [ShortUrlController::class, 'store'])->name('short-url.store');
    Route::get('/view-generate-url', [ShortUrlController::class, 'view'])->name('short-url.view');
    Route::get('/view-team-member', [ShortUrlController::class, 'viewTeamMember'])->name('viewTeamMember');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/admin/short-urls/download', [DashboardController::class, 'downloadAdmin'])->name('short-urls.download');
    Route::get('/admin/short-urls', [DashboardController::class, 'index'])->name('short-urls.filter');
    Route::get('/admin/view-short-urls', [DashboardController::class, 'viewAdminUrlFiltter'])->name('short-urls.viewFiltter');

});

Route::middleware(['auth','role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::post('/generate-url', [ShortUrlController::class, 'storeMember'])->name('short-url-member.store');
    Route::get('/member/short-urls/download', [DashboardController::class, 'downloadMember'])->name('short-urls.download');
    Route::get('/member/short-urls', [DashboardController::class, 'index'])->name('short-urls.filter');
});

// Accept Invitation (Guest)
Route::get('/invite/{token}', [InvitationController::class, 'show'])->name('invitations.show');
Route::post('/invite/{token}', [InvitationController::class, 'accept']) ->name('invitations.accept');

require __DIR__.'/auth.php';

Route::get('/{short_code}', [ShortUrlController ::class, 'redirect'])->name('short-url.redirect');
