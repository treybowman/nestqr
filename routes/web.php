<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicListingController;
use App\Http\Controllers\QrSlotController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDomainController;
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Stripe webhooks (must be before auth middleware)
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// QR Code redirect - the core public route
Route::get('/{shortCode}', [PublicListingController::class, 'redirect'])
    ->where('shortCode', '[A-Za-z0-9]{6}')
    ->name('public.listing')
    ->middleware('throttle:60,1');

// Auth routes (provided by Breeze, we'll define them manually for completeness)
require __DIR__.'/auth.php';

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // QR Slots
    Route::prefix('qr-codes')->name('qr-slots.')->group(function () {
        Route::get('/', [QrSlotController::class, 'index'])->name('index');
        Route::get('/{qrSlot}', [QrSlotController::class, 'show'])->name('show');
        Route::get('/{qrSlot}/download/{format}', [QrSlotController::class, 'download'])->name('download');
        Route::get('/{qrSlot}/assign', [PublicListingController::class, 'assign'])->name('assign');
    });

    // Listings
    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/', [ListingController::class, 'index'])->name('index');
        Route::get('/create', [ListingController::class, 'create'])->name('create');
        Route::get('/{listing}', [ListingController::class, 'show'])->name('show');
        Route::get('/{listing}/edit', [ListingController::class, 'edit'])->name('edit');
        Route::delete('/{listing}', [ListingController::class, 'destroy'])->name('destroy');
    });

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
        Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
        Route::get('/subscription/portal', [SubscriptionController::class, 'portal'])->name('subscription.portal');
    });

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::resource('users', AdminUserController::class)->except(['create', 'store']);
        Route::post('/users/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('/stop-impersonating', [AdminUserController::class, 'stopImpersonating'])->name('stop-impersonating');

        Route::resource('domains', AdminDomainController::class)->except(['show', 'edit', 'create']);
    });
});
