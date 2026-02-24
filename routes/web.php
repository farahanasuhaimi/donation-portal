<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\DonorAuthController;
use App\Http\Controllers\Auth\DonorGoogleAuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaign/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::post('/campaign/{campaign}/donate', [DonationController::class, 'store'])->name('campaigns.donate');

Route::get('/auth', [DonorAuthController::class, 'showAuth'])->name('auth.page');
Route::get('/login', [DonorAuthController::class, 'showLogin'])->name('donor.login');
Route::post('/login', [DonorAuthController::class, 'login'])->name('donor.login.submit');
Route::get('/register', [DonorAuthController::class, 'showRegister'])->name('donor.register');
Route::post('/register', [DonorAuthController::class, 'register'])->name('donor.register.submit');
Route::post('/auth/login', [DonorAuthController::class, 'login'])->name('auth.login.submit');
Route::post('/auth/register', [DonorAuthController::class, 'register'])->name('auth.register.submit');
Route::post('/logout', [DonorAuthController::class, 'logout'])->name('donor.logout');
Route::get('/auth/google/redirect', [DonorGoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [DonorGoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/organizer/login', function () {
    return redirect()->route('auth.page', ['mode' => 'login', 'role' => 'organizer']);
})->name('organizer.login');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminCampaignController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/campaign/create', [AdminCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaign', [AdminCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaign/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaign/{campaign}', [AdminCampaignController::class, 'update'])->name('campaigns.update');
    Route::patch('/campaign/{campaign}/donations/{donation}', [AdminCampaignController::class, 'confirmDonation'])->name('campaigns.donations.confirm');
    Route::delete('/campaign/{campaign}', [AdminCampaignController::class, 'destroy'])->name('campaigns.destroy');
});
