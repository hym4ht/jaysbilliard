<?php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\WebhookController;

// Webhook Midtrans (No CSRF needed, see bootstrap/app.php)
Route::post('/api/webhook/midtrans', [WebhookController::class, 'midtransHandler']);
Route::post('/v1.0/debit/notify', [WebhookController::class, 'snapBiDirectDebitHandler']);

// Public pages
Route::get('/', [HomeController::class , 'index'])->name('home');

Route::get('/rates', [RateController::class , 'index'])->name('rates');
Route::get('/location', [HomeController::class , 'location'])->name('location');

// Promos
Route::get('/promos', [PromoController::class , 'index'])->name('promos.index');

// Auth - Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class , 'login']);

    Route::get('/register', [AuthController::class , 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class , 'register']);
});

// Auth - Authenticated only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout'])->name('logout');
});

// Admin - Authenticated admins only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class , 'index'])->name('admin.dashboard');
    Route::get('/admin/meja', [MejaController::class, 'index'])->name('admin.meja.index');
    Route::get('/admin/history', [AdminDashboardController::class, 'history'])->name('admin.history');
    Route::post('/admin/booking/{id}/confirm', [AdminDashboardController::class, 'confirmBooking'])->name('admin.booking.confirm');
    Route::post('/admin/booking/{id}/end', [AdminDashboardController::class, 'endSession'])->name('admin.booking.end');
    Route::delete('/admin/booking/{id}', [AdminDashboardController::class, 'deleteBooking'])->name('admin.booking.delete');
    Route::post('/admin/booking/{id}/complete', [AdminDashboardController::class, 'completeBooking'])->name('admin.booking.complete');

    // Admin Menu Management
    Route::get('/admin/menu', [MenuController::class, 'index'])->name('admin.menu');
    Route::get('/admin/menu/baru', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/admin/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/admin/menu/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/admin/menu/{menu}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/admin/menu/{menu}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    Route::get('/admin/meja/baru', [MejaController::class, 'create'])->name('admin.meja.create');
    Route::post('/admin/meja', [MejaController::class, 'store'])->name('admin.meja.store');
    Route::get('/admin/meja/{table}/edit', [MejaController::class, 'edit'])->name('admin.meja.edit');
    Route::put('/admin/meja/{table}', [MejaController::class, 'update'])->name('admin.meja.update');
    Route::delete('/admin/meja/{table}', [MejaController::class, 'destroy'])->name('admin.meja.destroy');
    Route::get('/admin/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
});

// User - Authenticated customers only
Route::middleware(['auth', 'role:user'])->group(function () {
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');
    Route::get('/dashboard/history', [DashboardController::class, 'history'])->name('user.history');
    Route::get('/dashboard/meja', [DashboardController::class, 'meja'])->name('user.meja');
    Route::get('/dashboard/meja/availability', [DashboardController::class, 'mejaAvailability'])->name('user.meja.availability');
    Route::get('/dashboard/meja/konfirmasi', [DashboardController::class, 'konfirmasi'])->name('user.meja.konfirmasi');
    Route::get('/dashboard/fnb', [DashboardController::class, 'fnb'])->name('user.fnb');
    Route::get('/dashboard/fnb/konfirmasi', [DashboardController::class, 'fnbKonfirmasi'])->name('user.fnb.konfirmasi');
    Route::post('/dashboard/fnb/checkout', [DashboardController::class, 'fnbCheckout'])->name('user.fnb.checkout');
    Route::get('/dashboard/fnb/payment-status/{orderId}', [DashboardController::class, 'fnbPaymentStatus'])->name('user.fnb.payment-status');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('user.profile');

    // Booking
    Route::get('/booking', [BookingController::class , 'create'])->name('booking.create');
    Route::get('/booking/pilih-meja', [BookingController::class , 'selectTable'])->name('booking.select-table');
    Route::post('/booking', [BookingController::class , 'store'])->name('booking.store');
    Route::get('/booking/payment-status/{orderId}', [BookingController::class , 'paymentStatus'])->name('booking.payment-status');
});
