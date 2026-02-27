<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Guest
Route::get('/', HomeController::class)->name('home');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email verification (Mailtrap)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->where('token', '[a-zA-Z0-9]+');
Route::post('/email/verify/send', [EmailVerificationController::class, 'send'])->middleware('auth')->name('verification.send');
Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])->middleware('auth')->name('verification.resend');
Route::get('/email/verify/status', [EmailVerificationController::class, 'status'])->middleware('auth')->name('verification.status');
Route::get('/verification/success', [EmailVerificationController::class, 'success'])->name('verification.success');
Route::get('/verification/failed', [EmailVerificationController::class, 'failed'])->name('verification.failed');

// Authenticated
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin (FR3.2, FR3.3: /admin/* protected; 403 if not admin)
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');
    Route::post('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    
    // Product Management (FR2.1 - FR2.7)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{product}/force', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('/products/error-report/download', [ProductController::class, 'downloadErrorReport'])->name('products.error-report.download');
});
