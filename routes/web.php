<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\CarApprovalController;
use App\Http\Controllers\DuplicateCarController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/index', [IndexController::class, 'index'])->name('index');
Route::get('/index.html', [IndexController::class, 'index'])->name('index.html');

// Email verification routes (Laravel built-in)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi baru sudah dikirim ke email Anda.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Public Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public Comment Routes (auth checked in controller)
Route::post('/blog/{slug}/comment', [CommentController::class, 'store'])->name('comments.store');
Route::get('/about', [AboutController::class, 'index'])->name('about');
//car - Listing bisa dilihat semua, detail perlu login
Route::get('/car', [CarController::class, 'show'])->name('cars');
Route::middleware(['auth'])->group(function () {
    Route::get('/car/{id}', [CarController::class, 'showDetail'])->name('car.details');
});

// Contact Routes (Auth checked in controller)
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// CRUD Mobil (Protected by auth and role middleware)
Route::middleware(['auth'])->group(function () {
    // Cars index - Admin and Seller only
    Route::get('/cars', [CarController::class, 'index'])->middleware('role:admin,seller')->name('cars.index');

    // Cars create - Admin and Seller only
    Route::get('/cars/create', [CarController::class, 'create'])->middleware('role:admin,seller')->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->middleware('role:admin,seller')->name('cars.store');

    // Cars edit/update/delete - Admin and Seller (with ownership check)
    Route::get('/cars/{id}/edit', [CarController::class, 'edit'])->middleware('role:admin,seller')->name('cars.edit');
    Route::put('/cars/{id}', [CarController::class, 'update'])->middleware('role:admin,seller')->name('cars.update');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->middleware('role:admin,seller')->name('cars.destroy');

    // Users Management (Admin Only)
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/sellers', [UserController::class, 'sellers'])->name('sellers');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Blog Management (Admin Only)
    Route::prefix('admin/blogs')->name('blogs.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [BlogController::class, 'adminIndex'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
    });

    // Testimonials Management (Admin Only)
    Route::prefix('admin/testimonials')->name('testimonials.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/create', [TestimonialController::class, 'create'])->name('create');
        Route::post('/', [TestimonialController::class, 'store'])->name('store');
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('edit');
        Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');
    });

    // Comment Management (Admin Only)
    Route::prefix('admin/comments')->name('comments.admin.')->middleware('role:admin')->group(function () {
        Route::get('/', [CommentController::class, 'adminIndex'])->name('index');
        Route::post('/{id}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CommentController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Car Approval Management (Admin Only)
    Route::prefix('admin/car-approvals')->name('admin.car-approvals.')->middleware('role:admin')->group(function () {
        Route::get('/', [CarApprovalController::class, 'index'])->name('index');
        Route::get('/history', [CarApprovalController::class, 'history'])->name('history');
        Route::get('/{car}', [CarApprovalController::class, 'show'])->name('show');
        Route::post('/{car}/approve', [CarApprovalController::class, 'approve'])->name('approve');
        Route::post('/{car}/reject', [CarApprovalController::class, 'reject'])->name('reject');
    });

    // Duplicate Cars Management (Admin Only)
    Route::prefix('admin/duplicate-cars')->name('admin.duplicate-cars.')->middleware('role:admin')->group(function () {
        Route::get('/', [DuplicateCarController::class, 'index'])->name('index');
    });

    // Chat Management
    Route::prefix('chat')->name('chat.')->group(function () {
        // Buyer routes
        Route::middleware('role:buyer')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('/seller/{sellerId}', [ChatController::class, 'showSeller'])->name('seller');
        });

        // Seller routes
        Route::middleware('role:seller')->group(function () {
            Route::get('/seller-chats', [ChatController::class, 'sellerIndex'])->name('seller.index');
        });

        // Common routes (both buyer and seller)
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{chatId}/mark-read', [ChatController::class, 'markAsRead'])->name('mark-read');
        Route::get('/{chatId}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chatId}/message', [ChatController::class, 'store'])->name('store');
        Route::post('/{chatId}/reply', [ChatController::class, 'reply'])->name('reply');
        Route::put('/{chatId}/message/{messageId}', [ChatController::class, 'edit'])->name('edit');
        Route::delete('/{chatId}/message/{messageId}', [ChatController::class, 'delete'])->name('delete');
        Route::get('/{chatId}/messages', [ChatController::class, 'getMessages'])->name('messages');
        Route::delete('/delete', [ChatController::class, 'destroy'])->name('destroy');
        Route::delete('/{chatId}', [ChatController::class, 'destroySingle'])->name('destroy.single');
    });

    // Wishlist Management (Buyer Only)
    Route::prefix('wishlist')->name('wishlist.')->middleware('role:buyer')->group(function () {
        Route::post('/{car}', [WishlistController::class, 'store'])->name('store');
        Route::delete('/{car}', [WishlistController::class, 'destroy'])->name('destroy');
    });

    // Cart Management (Buyer Only)
    Route::prefix('cart')->name('cart.')->middleware('role:buyer')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/{car}', [CartController::class, 'store'])->name('store');
        Route::put('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('destroy');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });

    // Reports Management
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/{car}', [ReportController::class, 'store'])->name('store');
        Route::get('/my-reports', [ReportController::class, 'myReports'])->name('my-reports');
    });

    // Reports Management (Admin Only)
    Route::prefix('admin/reports')->name('admin.reports.')->middleware('role:admin')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::put('/{report}', [ReportController::class, 'update'])->name('update');
        Route::post('/{report}/unpublish-car', [ReportController::class, 'unpublishCar'])->name('unpublish-car');
    });

    // Reports Management (Seller Only)
    Route::prefix('seller/reports')->name('seller.reports.')->middleware('role:seller')->group(function () {
        Route::get('/', [ReportController::class, 'sellerIndex'])->name('index');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
    });
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/register/verify', [AuthController::class, 'showVerifyForm'])->name('register.verify');
Route::post('/register/verify', [AuthController::class, 'verifyCode'])->name('register.verify');
Route::post('/register/resend', [AuthController::class, 'resendCode'])->name('register.resend');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
