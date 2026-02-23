<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Organizer\AuthController as OrganizerAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaign/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::post('/campaign/{campaign}/donate', [DonationController::class, 'store'])->name('campaigns.donate');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::get('/organizer/login', [OrganizerAuthController::class, 'showLogin'])->name('organizer.login');
Route::post('/organizer/login', [OrganizerAuthController::class, 'login'])->name('organizer.login.submit');
Route::post('/organizer/logout', [OrganizerAuthController::class, 'logout'])->name('organizer.logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminCampaignController::class, 'index'])->name('dashboard');
    Route::get('/campaign/create', [AdminCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaign', [AdminCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaign/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaign/{campaign}', [AdminCampaignController::class, 'update'])->name('campaigns.update');
});
