<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\TrashController as AdminTrashController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Guest
Route::get('/', HomeController::class)->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
// Public product detail for customers
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/register', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
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
Route::get('/verification/success', [EmailVerificationController::class, 'success'])->name('verification.success');
Route::get('/verification/failed', [EmailVerificationController::class, 'failed'])->name('verification.failed');

// Authenticated (FR4: Shopping cart — customers only need auth+verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart_item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart_item}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Reviews (FR9)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
});

// Admin (FR3.2, FR3.3: /admin/* protected; 403 if not admin)
Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');
    Route::post('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Trash Management
    Route::get('/trash', [AdminTrashController::class, 'index'])->name('trash.index');

    // Product Management (FR2.1 - FR2.7)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{product}/force', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::post('/products/bulk-restore', [ProductController::class, 'bulkRestore'])->name('products.bulk-restore');
    Route::delete('/products/bulk-force-delete', [ProductController::class, 'bulkForceDelete'])->name('products.bulk-force-delete');
    Route::delete('/products/empty-trash', [ProductController::class, 'emptyTrash'])->name('products.empty-trash');
    Route::get('/products/error-report/download', [ProductController::class, 'downloadErrorReport'])->name('products.error-report.download');

    // Reviews Management (FR9.3, FR9.4)
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Reports & Analytics (FR11)
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/dashboard', [AdminReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/yearly-sales', [AdminReportController::class, 'yearlySales'])->name('reports.yearly-sales');
    Route::get('/reports/monthly-sales', [AdminReportController::class, 'monthlySales'])->name('reports.monthly-sales');
    Route::get('/reports/date-range-sales', [AdminReportController::class, 'dateRangeSales'])->name('reports.date-range-sales');
    Route::get('/reports/product-sales', [AdminReportController::class, 'productSales'])->name('reports.product-sales');
});
